<?php

namespace App\Filament\Widgets;

use App\Models\Alumni;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\GalleryMedia;
use App\Models\NewsPost;
use App\Models\User;
use App\Support\AdminPermissions;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class ContentStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::name('view', 'dashboard')) ?? false;
    }

    protected function getStats(): array
    {
        $stats = Cache::remember('dashboard.content-stats', now()->addMinutes(10), fn (): array => [
            'events' => Event::query()->count(),
            'gallery_media' => GalleryMedia::query()->count(),
            'gallery_albums' => GalleryAlbum::query()->count(),
            'news_posts' => NewsPost::query()->count(),
            'users' => User::query()->count(),
            'active_users' => User::query()->where('is_active', true)->count(),
            'alumni' => Alumni::query()->count(),
            'distinguished_alumni' => Alumni::query()->where('is_distinguished', true)->count(),
            'new_messages' => ContactMessage::query()->where('status', 'new')->count(),
        ]);

        return [
            Stat::make('Events', $stats['events'])->description('Total event records'),
            Stat::make('Gallery Items', $stats['gallery_media'])->description($stats['gallery_albums'].' albums'),
            Stat::make('News & Updates', $stats['news_posts'])->description('Announcements, newsletters, achievements'),
            Stat::make('Admin Users', $stats['users'])->description($stats['active_users'].' active'),
            Stat::make('Alumni', $stats['alumni'])->description($stats['distinguished_alumni'].' distinguished'),
            Stat::make('New Messages', $stats['new_messages'])->description('Unread contact submissions'),
        ];
    }
}
