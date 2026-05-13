<?php

namespace App\Filament\Widgets;

use App\Support\AdminPermissions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Models\Activity;

class RecentActivityWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::name('view', 'activity')) ?? false;
    }

    protected function getTableQuery(): Builder
    {
        return Activity::query()->latest()->limit(10);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->heading('Recent Activity')
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('event')->badge(),
                TextColumn::make('description')->limit(70),
                TextColumn::make('causer.name')->label('User'),
            ]);
    }
}
