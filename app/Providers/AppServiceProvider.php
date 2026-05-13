<?php

namespace App\Providers;

use App\Models\SiteSetting;
use App\Models\AdminRole;
use App\Policies\ActivityPolicy;
use App\Policies\RolePolicy;
use App\Support\AdminPermissions;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Spatie\Activitylog\Models\Activity;

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
        Gate::before(function ($user, string $ability): ?bool {
            if ($user->hasRole('Super Admin')) {
                return true;
            }

            return null;
        });

        Gate::policy(AdminRole::class, RolePolicy::class);
        Gate::policy(Activity::class, ActivityPolicy::class);

        foreach (AdminPermissions::all() as $permission) {
            Gate::define($permission, fn ($user): bool => $user->hasPermissionTo($permission));
        }

        Event::listen(Login::class, function (Login $event): void {
            activity('auth')
                ->causedBy($event->user)
                ->event('login')
                ->log('Logged in');
        });

        Event::listen(Logout::class, function (Logout $event): void {
            if ($event->user) {
                activity('auth')
                    ->causedBy($event->user)
                    ->event('logout')
                    ->log('Logged out');
            }
        });

        View::composer('*', function ($view): void {
            try {
                $siteSetting = SiteSetting::current();
            } catch (\Throwable) {
                $siteSetting = null;
            }

            $view->with('siteSetting', $siteSetting);
        });
    }
}
