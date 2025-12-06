<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('site_settings')) {
                $settings = SiteSetting::query()->orderBy('id')->first();
                View::share('siteSettings', $settings);
            }
        } catch (QueryException $e) {
            // Database connection / migration not ready yet (e.g. during fresh install or when DB is down).
            // Silently skip sharing settings to avoid breaking artisan commands.
        } catch (\Throwable $e) {
            // Any other unexpected issue while resolving settings should not block the app from booting.
        }
    }
}
