<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AlumniResource\Pages;
use App\Models\Alumni;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AlumniResource extends AuthorizedResource
{
    protected static ?string $model = Alumni::class;

    protected static ?string $permissionModule = 'alumni';

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Alumni & Career';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->maxLength(255),
            TextInput::make('graduation_year')
                ->required()
                ->numeric()
                ->minValue(1900)
                ->maxValue((int) date('Y') + 10),
            TextInput::make('degree')->maxLength(255),
            TextInput::make('department')->maxLength(255),
            TextInput::make('current_position')->maxLength(255),
            TextInput::make('organization')->maxLength(255),
            TextInput::make('location')->maxLength(255),
            FileUpload::make('profile_photo_path')
                ->label('Profile Photo')
                ->directory('alumni')
                ->disk('public')
                ->image()
                ->imageEditor(),
            Textarea::make('achievements')->rows(4)->columnSpanFull(),
            Toggle::make('is_distinguished')->label('Distinguished Alumni'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo_path')->label('Photo')->disk('public')->circular(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('graduation_year')->sortable(),
                TextColumn::make('department')->searchable(),
                TextColumn::make('location')->searchable(),
                IconColumn::make('is_distinguished')->boolean()->label('Featured'),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('graduation_year')
                    ->options(fn (): array => Alumni::query()
                        ->select('graduation_year')
                        ->distinct()
                        ->orderByDesc('graduation_year')
                        ->pluck('graduation_year', 'graduation_year')
                        ->map(fn ($year) => (string) $year)
                        ->all()),
                SelectFilter::make('department')
                    ->options(fn (): array => Alumni::query()
                        ->whereNotNull('department')
                        ->select('department')
                        ->distinct()
                        ->orderBy('department')
                        ->pluck('department', 'department')
                        ->all()),
                SelectFilter::make('location')
                    ->options(fn (): array => Alumni::query()
                        ->whereNotNull('location')
                        ->select('location')
                        ->distinct()
                        ->orderBy('location')
                        ->pluck('location', 'location')
                        ->all()),
                TernaryFilter::make('is_distinguished')->label('Distinguished'),
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
            'index' => Pages\ListAlumnis::route('/'),
            'create' => Pages\CreateAlumni::route('/create'),
            'edit' => Pages\EditAlumni::route('/{record}/edit'),
        ];
    }
}
