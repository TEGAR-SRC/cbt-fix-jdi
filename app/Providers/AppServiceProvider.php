<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use App\Models\Setting;

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
        Inertia::share('branding', function () {
            $defaults = [
                'site_name' => 'CBT AI',
                'cbt_name' => 'Ujian Online',
                'school_logo' => null,
            ];
            $db = Setting::query()->pluck('value', 'key')->toArray();
            return array_merge($defaults, $db);
        });
    }
}
