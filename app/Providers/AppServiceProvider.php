<?php

namespace App\Providers;

use App\Models\AdminRole;
use App\Models\Alumni;
use App\Models\BylawDocument;
use App\Models\Chapter;
use App\Models\CommitteeMember;
use App\Models\ContactMessage;
use App\Models\DonationCampaign;
use App\Models\Event as AlumniEvent;
use App\Models\GalleryAlbum;
use App\Models\GalleryMedia;
use App\Models\JobPosting;
use App\Models\MentorshipProgram;
use App\Models\NewsPost;
use App\Models\Scholarship;
use App\Models\SiteSetting;
use App\Models\StaticPage;
use App\Policies\ActivityPolicy;
use App\Policies\RolePolicy;
use App\Support\AdminPermissions;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Cache;
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

        $flushPerformanceCache = function (): void {
            Cache::forget('site-settings.current');
            Cache::forget('home.sections');
            Cache::forget('home.featured-alumni');
            Cache::forget('home.upcoming-events');
            Cache::forget('home.news-posts');
            Cache::forget('home.gallery-preview');
            Cache::forget('about.sections');
            Cache::forget('about.governing-body');
            Cache::forget('about.executive-committee');
            Cache::forget('about.office-bearers');
            Cache::forget('about.chapters');
            Cache::forget('about.bylaws');
            Cache::forget('alumni.filters');
            Cache::forget('alumni.distinguished');
            Cache::forget('career.intro');
            Cache::forget('contributions.intro');
            Cache::forget('contributions.campaigns');
            Cache::forget('contributions.scholarships');
            Cache::forget('contact.intro');
            Cache::forget('dashboard.content-stats');
        };

        foreach ([
            Alumni::class,
            AlumniEvent::class,
            BylawDocument::class,
            Chapter::class,
            CommitteeMember::class,
            ContactMessage::class,
            DonationCampaign::class,
            GalleryAlbum::class,
            GalleryMedia::class,
            JobPosting::class,
            MentorshipProgram::class,
            NewsPost::class,
            Scholarship::class,
            SiteSetting::class,
            StaticPage::class,
        ] as $model) {
            $model::saved($flushPerformanceCache);
            $model::deleted($flushPerformanceCache);
        }

        try {
            $siteSetting = Cache::remember('site-settings.current', now()->addHour(), fn () => SiteSetting::current());
        } catch (\Throwable) {
            $siteSetting = null;
        }

        View::share('siteSetting', $siteSetting);
    }
}
