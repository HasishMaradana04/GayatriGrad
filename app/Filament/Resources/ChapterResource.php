<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterResource\Pages;
use App\Models\Chapter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ChapterResource extends AuthorizedResource
{
    protected static ?string $model = Chapter::class;

    protected static ?string $permissionModule = 'chapters';

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            Select::make('chapter_type')
                ->required()
                ->options([
                    'local' => 'Local',
                    'international' => 'International',
                ])
                ->default('local'),
            TextInput::make('location')->maxLength(255),
            TextInput::make('contact_person')->maxLength(255),
            TextInput::make('email')->email()->maxLength(255),
            TextInput::make('phone')->maxLength(255),
            Toggle::make('is_active')->default(true),
            Textarea::make('description')->rows(4)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('chapter_type')->badge(),
                TextColumn::make('location')->searchable(),
                TextColumn::make('contact_person')->searchable(),
                TextColumn::make('email')->searchable(),
            ])
            ->filters([
                SelectFilter::make('chapter_type')
                    ->options([
                        'local' => 'Local',
                        'international' => 'International',
                    ]),
                TernaryFilter::make('is_active'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChapters::route('/'),
            'create' => Pages\CreateChapter::route('/create'),
            'edit' => Pages\EditChapter::route('/{record}/edit'),
        ];
    }
}
