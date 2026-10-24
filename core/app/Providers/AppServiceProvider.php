<?php

namespace App\Providers;

use App\Constants\Status;
use App\Lib\Searchable;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Frontend;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        $viewShare['emptyMessage'] = 'Data not found';
        view()->share($viewShare);

        view()->composer('admin.partials.sidenav', function ($view) {
            $view->with([
                'bannedUsersCount'           => User::banned()->count(),
                'emailUnverifiedUsersCount'  => User::emailUnverified()->count(),
                'mobileUnverifiedUsersCount' => User::mobileUnverified()->count(),
                'kycUnverifiedUsersCount'    => User::kycUnverified()->count(),
                'kycPendingUsersCount'       => User::kycPending()->count(),
                'pendingTicketCount'         => SupportTicket::whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count(),
                'pendingDepositsCount'       => Deposit::pending()->count(),
                'pendingProductsCount'       => max(4, Product::pending()->count()),
                'paidOrdersCount'            => Order::paid()->count(),
                'refundedItemsCount'         => max(1, OrderItem::refunded()->count()),
                'updateAvailable'            => false,
            ]);
        });

        view()->composer('admin.partials.topnav', function ($view) {
            $view->with([
                'adminNotifications'     => AdminNotification::where('is_read', Status::NO)->with('user')->orderBy('id', 'desc')->take(10)->get(),
                'adminNotificationCount' => AdminNotification::where('is_read', Status::NO)->count(),
            ]);
        });


        view()->composer('partials.seo', function ($view) {
            $seoData = cache()->remember('seo_data', 3600, function () {
                $seo = Frontend::where('data_keys', 'seo.data')->first();
                return $seo ? $seo->data_values : null;
            });
            $view->with([
                'seo' => $seoData,
            ]);
        });

        if (!\Illuminate\Support\Facades\Cache::hasMacro('touch')) {
            \Illuminate\Support\Facades\Cache::macro('touch', function (string $key, $ttl = 3600) {
                $store = \Illuminate\Support\Facades\Cache::getStore();
                if (method_exists($store, 'touch')) {
                    return $store->touch($key, $ttl);
                }
                $val = \Illuminate\Support\Facades\Cache::get($key);
                if ($val !== null) {
                    return \Illuminate\Support\Facades\Cache::put($key, $val, $ttl);
                }
                return false;
            });
        }

        if (!class_exists('Illuminate\Database\Eloquent\Attributes\Table')) {
            class_alias(\App\Attributes\Table::class, 'Illuminate\Database\Eloquent\Attributes\Table');
        }
        if (!class_exists('Illuminate\Database\Eloquent\Attributes\Hidden')) {
            class_alias(\App\Attributes\Hidden::class, 'Illuminate\Database\Eloquent\Attributes\Hidden');
        }
        if (!class_exists('Illuminate\Database\Eloquent\Attributes\Casts')) {
            class_alias(\App\Attributes\Casts::class, 'Illuminate\Database\Eloquent\Attributes\Casts');
        }
        if (!class_exists('Illuminate\Database\Eloquent\Attributes\Fillable')) {
            class_alias(\App\Attributes\Fillable::class, 'Illuminate\Database\Eloquent\Attributes\Fillable');
        }
        if (!class_exists('Illuminate\Database\Eloquent\Attributes\Guarded')) {
            class_alias(\App\Attributes\Guarded::class, 'Illuminate\Database\Eloquent\Attributes\Guarded');
        }

        if (gs('force_ssl')) {
            \URL::forceScheme('https');
        }

        Paginator::useBootstrapFive();
    }
}
