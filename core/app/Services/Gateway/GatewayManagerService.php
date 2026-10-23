<?php

namespace App\Services\Gateway;

use App\Models\Form;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Services\Composer\ComposerService;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class GatewayManagerService {

    protected ComposerService $composer;
    protected Filesystem $files;

    /**
     * Protected core database tables that must never be dropped
     */
    protected array $protectedTables = [
        'activities', 'admin_notifications', 'admin_password_resets', 'admins',
        'advertisements', 'api_keys', 'campaign_products', 'campaigns', 'carts',
        'categories', 'changelogs', 'comments', 'coupons', 'cron_job_logs',
        'cron_jobs', 'cron_schedules', 'deposits', 'device_tokens', 'downloads',
        'extensions', 'forms', 'frontends', 'gateway_currencies', 'gateways',
        'general_settings', 'languages', 'notification_logs', 'notification_templates',
        'order_items', 'orders', 'pages', 'password_resets', 'product_rating',
        'product_user', 'product_views', 'products', 'ratings', 'refund_activities',
        'refund_requests', 'reported_reviews', 'reported_reviews_attachments',
        'review_categories', 'reviews', 'sub_categories', 'subscribers',
        'support_attachments', 'support_messages', 'support_tickets',
        'transactions', 'update_logs', 'user_follows', 'user_logins', 'users',
        'migrations', 'failed_jobs', 'personal_access_tokens'
    ];

    public function __construct(ComposerService $composer, Filesystem $files) {
        $this->composer = $composer;
        $this->files    = $files;
    }

    /**
     * Auto-scaffold a dedicated ProcessController extending dynamic engine
     */
    public function scaffoldProcessController(Gateway $gateway): bool {
        try {
            $studly = Str::studly($gateway->alias);
            $dir    = app_path("Http/Controllers/Gateway/{$studly}");

            if (!$this->files->isDirectory($dir)) {
                $this->files->makeDirectory($dir, 0755, true);
            }

            $file = "{$dir}/ProcessController.php";
            if (!$this->files->exists($file)) {
                $content = "<?php\n\nnamespace App\\Http\\Controllers\\Gateway\\{$studly};\n\nuse App\\Http\\Controllers\\Gateway\\Dynamic\\ProcessController as DynamicProcessor;\n\nclass ProcessController extends DynamicProcessor {\n    // Auto-generated dynamic processor for {$gateway->name}\n}\n";
                $this->files->put($file, $content);
            }

            return true;
        } catch (\Throwable $e) {
            Log::warning("GatewayManagerService: Failed scaffolding controller for {$gateway->alias}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Advanced gateway deletion and complete codebase / dependency cleanup
     */
    public function deleteGateway(Gateway $gateway, array $options = []): array {
        $removeFiles    = $options['remove_files'] ?? true;
        $removePackages = $options['remove_packages'] ?? true;
        $removeTables   = $options['remove_tables'] ?? true;

        $name         = $gateway->name;
        $alias        = $gateway->alias;
        $code         = $gateway->code;
        $cleanedItems = [];

        // 1. Delete associated GatewayCurrency records
        $currencyCount = GatewayCurrency::where('method_code', $code)->count();
        GatewayCurrency::where('method_code', $code)->delete();
        $cleanedItems[] = "{$currencyCount} currency setting(s)";

        // 2. Delete Form fields if exists
        if ($gateway->form_id) {
            Form::where('id', $gateway->form_id)->delete();
            $cleanedItems[] = "form schema";
        }

        // 3. Delete logo image
        if ($gateway->image) {
            try {
                fileManager()->removeFile(getFilePath('gateway') . '/' . $gateway->image);
                $cleanedItems[] = "logo asset";
            } catch (\Throwable $e) {}
        }

        // 4. Codebase Files Cleanup (Controllers, Views, Configs)
        if ($removeFiles) {
            $studly = Str::studly($alias);
            $dirs = [
                app_path("Http/Controllers/Gateway/{$studly}"),
                app_path("Http/Controllers/Gateway/{$alias}"),
            ];
            foreach ($dirs as $dir) {
                if ($this->files->isDirectory($dir)) {
                    $this->files->deleteDirectory($dir);
                    $cleanedItems[] = "controller directory (" . basename($dir) . ")";
                }
            }

            $views = [
                resource_path("views/templates/basic/user/payment/{$studly}.blade.php"),
                resource_path("views/templates/basic/user/payment/{$alias}.blade.php"),
            ];
            foreach ($views as $view) {
                if ($this->files->exists($view)) {
                    $this->files->delete($view);
                    $cleanedItems[] = "payment view (" . basename($view) . ")";
                }
            }

            $config = config_path("{$alias}.php");
            if ($this->files->exists($config)) {
                $this->files->delete($config);
                $cleanedItems[] = "config ({$alias}.php)";
            }
        }

        // 5. Custom Database Tables Cleanup
        if ($removeTables) {
            $customTables = $gateway->getCustomTables();
            $potentialTables = array_unique(array_merge($customTables, [
                "gateway_{$alias}",
                "{$alias}_transactions",
                "{$alias}_logs",
                "{$alias}_payments",
            ]));

            Schema::disableForeignKeyConstraints();
            foreach ($potentialTables as $tbl) {
                $tbl = trim($tbl);
                if (!empty($tbl) && !in_array($tbl, $this->protectedTables) && Schema::hasTable($tbl)) {
                    Schema::dropIfExists($tbl);
                    $cleanedItems[] = "table `{$tbl}`";
                }
            }
            Schema::enableForeignKeyConstraints();
        }

        // 6. Composer Library Dependencies Removal
        if ($removePackages) {
            $packagesToRemove = $gateway->getPackages();

            if (!empty($packagesToRemove)) {
                // Ensure no other active gateway is sharing this package
                $otherGateways = Gateway::where('id', '!=', $gateway->id)->get();
                $stillNeeded = [];
                foreach ($otherGateways as $og) {
                    foreach ($og->getPackages() as $p) {
                        $stillNeeded[$p] = $og->name;
                    }
                }

                $uninstalledPackages = [];
                foreach ($packagesToRemove as $pkg) {
                    if (isset($stillNeeded[$pkg])) {
                        $cleanedItems[] = "package `{$pkg}` retained (used by {$stillNeeded[$pkg]})";
                    } else {
                        $uninstalledPackages[] = $pkg;
                    }
                }

                if (!empty($uninstalledPackages)) {
                    $this->composer->remove($uninstalledPackages, dumpAutoload: true);
                    foreach ($uninstalledPackages as $upkg) {
                        $cleanedItems[] = "package `{$upkg}` uninstalled";
                    }
                }
            }
        }

        // 7. Delete Gateway record
        $gateway->delete();

        // 8. Re-optimize Laravel bootstrap caches
        try {
            Artisan::call('optimize:clear');
        } catch (\Throwable $e) {}

        $cleanedStr = !empty($cleanedItems) ? implode(', ', $cleanedItems) : 'all associated assets';
        $message    = "Gateway '{$name}' deleted. Codebase cleaned: {$cleanedStr}.";

        return [
            'success' => true,
            'cleaned' => $cleanedItems,
            'message' => $message,
        ];
    }
}
