<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Support\AdminPermissions;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestContactMessagesWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return auth()->user()?->can(AdminPermissions::name('view', 'contact-messages')) ?? false;
    }

    protected function getTableQuery(): Builder
    {
        return ContactMessage::query()->latest()->limit(8);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->heading('Latest Contact Messages')
            ->paginated(false)
            ->columns([
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email'),
                TextColumn::make('subject')->limit(50),
                TextColumn::make('status')->badge(),
            ]);
    }
}
