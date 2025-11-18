<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Ambil data hanya sekali dan cache untuk performa
            $settings = Cache::rememberForever('settings', function () {
                return Setting::pluck('value', 'key')->all();
            });
            // $settings = \Illuminate\Support\Facades\Cache::remember('settings', 60, function () {
            //     return Setting::all()->keyBy('key');
            // });
            $view->with('settings', $settings);
        });
    }
}
