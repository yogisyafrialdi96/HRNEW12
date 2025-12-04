<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Register Blade directives for permissions
        Blade::if('hasPermission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermissionTo($permission);
        });

        Blade::if('hasAnyPermission', function (...$permissions) {
            if (!auth()->check()) {
                return false;
            }

            foreach ($permissions as $permission) {
                if (auth()->user()->hasPermissionTo($permission)) {
                    return true;
                }
            }

            return false;
        });

        Blade::if('hasAllPermissions', function (...$permissions) {
            if (!auth()->check()) {
                return false;
            }

            foreach ($permissions as $permission) {
                if (!auth()->user()->hasPermissionTo($permission)) {
                    return false;
                }
            }

            return true;
        });

        Blade::if('hasRole', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('hasAnyRole', function (...$roles) {
            if (!auth()->check()) {
                return false;
            }

            foreach ($roles as $role) {
                if (auth()->user()->hasRole($role)) {
                    return true;
                }
            }

            return false;
        });
    }
}

