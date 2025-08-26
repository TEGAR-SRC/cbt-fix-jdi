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

        // Share authenticated user basic data (role, name, subject) for role-based UI (sidebar, dashboards)
        Inertia::share('auth', function () {
            $user = auth()->user();
            if(!$user) return null;
            return [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role,
                    'subject' => $user->subject ?? null,
                ]
            ];
        });
    }
}
