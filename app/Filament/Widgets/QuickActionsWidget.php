<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EventResource;
use App\Filament\Resources\GalleryAlbumResource;
use App\Filament\Resources\NewsPostResource;
use App\Filament\Resources\UserResource;
use App\Support\AdminPermissions;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-actions';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::name('view', 'dashboard')) ?? false;
    }

    public function getActions(): array
    {
        return array_values(array_filter([
            EventResource::canCreate() ? ['label' => 'Create Event', 'url' => EventResource::getUrl('create')] : null,
            GalleryAlbumResource::canCreate() ? ['label' => 'Create Gallery Album', 'url' => GalleryAlbumResource::getUrl('create')] : null,
            NewsPostResource::canCreate() ? ['label' => 'Create News Post', 'url' => NewsPostResource::getUrl('create')] : null,
            UserResource::canCreate() ? ['label' => 'Create Admin User', 'url' => UserResource::getUrl('create')] : null,
        ]));
    }
}
