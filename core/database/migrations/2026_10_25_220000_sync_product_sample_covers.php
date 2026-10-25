<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        try {
            $products = Product::all();
            foreach ($products as $p) {
                if (empty($p->slug)) {
                    continue;
                }
                $relDir = 'assets/files/product/' . $p->slug;
                $scanDir = file_exists($relDir) ? $relDir : (file_exists(base_path('../' . $relDir)) ? base_path('../' . $relDir) : null);
                if ($scanDir && is_dir($scanDir)) {
                    $files = glob($scanDir . '/*.{png,jpg,jpeg,webp,PNG,JPG,JPEG,WEBP}', GLOB_BRACE);
                    if (!empty($files)) {
                        $latestFile = basename($files[0]);
                        // Find latest file matching sample_cover_ or cover_
                        foreach ($files as $f) {
                            if (str_contains(basename($f), 'sample_cover_')) {
                                $latestFile = basename($f);
                                break;
                            }
                        }
                        $p->thumbnail = $latestFile;
                        $p->preview_image = $latestFile;
                        $p->save();
                    }
                }
            }
        } catch (\Throwable $e) {
            // graceful fallback
        }
    }

    public function down(): void
    {
        // No-op
    }
};
