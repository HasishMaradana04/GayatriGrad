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

class ContentStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::name('view', 'dashboard')) ?? false;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Events', Event::query()->count())->description('Total event records'),
            Stat::make('Gallery Items', GalleryMedia::query()->count())->description(GalleryAlbum::query()->count().' albums'),
            Stat::make('News & Updates', NewsPost::query()->count())->description('Announcements, newsletters, achievements'),
            Stat::make('Admin Users', User::query()->count())->description(User::query()->where('is_active', true)->count().' active'),
            Stat::make('Alumni', Alumni::query()->count())->description(Alumni::query()->where('is_distinguished', true)->count().' distinguished'),
            Stat::make('New Messages', ContactMessage::query()->where('status', 'new')->count())->description('Unread contact submissions'),
        ];
    }
}
