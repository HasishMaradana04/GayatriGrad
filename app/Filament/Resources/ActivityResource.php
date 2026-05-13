<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityResource\Pages;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class ActivityResource extends AuthorizedResource
{
    protected static ?string $model = Activity::class;

    protected static ?string $permissionModule = 'activity';

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Administration';

    public static function getNavigationLabel(): string
    {
        return 'Activity Log';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('log_name')->disabled(),
            TextInput::make('event')->disabled(),
            TextInput::make('causer.name')->label('Causer')->disabled(),
            TextInput::make('subject_type')->disabled(),
            TextInput::make('subject_id')->disabled(),
            Textarea::make('description')->disabled()->columnSpanFull(),
            KeyValue::make('properties')->disabled()->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('event')->badge(),
                TextColumn::make('description')->searchable()->limit(60),
                TextColumn::make('causer.name')->label('User')->searchable(),
                TextColumn::make('subject_type')->formatStateUsing(fn (?string $state): string => class_basename((string) $state))->label('Subject'),
                TextColumn::make('subject_id')->label('ID'),
            ])
            ->filters([
                SelectFilter::make('event')->options([
                    'created' => 'Created',
                    'updated' => 'Updated',
                    'deleted' => 'Deleted',
                    'login' => 'Login',
                    'logout' => 'Logout',
                ]),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
        ];
    }
}
