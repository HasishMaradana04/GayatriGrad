<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BylawDocumentResource\Pages;
use App\Models\BylawDocument;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BylawDocumentResource extends AuthorizedResource
{
    protected static ?string $model = BylawDocument::class;

    protected static ?string $permissionModule = 'bylaws';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Content Management';

    public static function getNavigationLabel(): string
    {
        return 'Bylaws';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->required()->maxLength(255),
            FileUpload::make('file_path')
                ->required()
                ->directory('bylaws')
                ->disk('public')
                ->openable()
                ->downloadable(),
            Toggle::make('is_active')->default(true),
            DateTimePicker::make('published_at')->seconds(false),
            Textarea::make('description')->rows(4)->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
                TextColumn::make('published_at')->dateTime()->sortable(),
                TextColumn::make('updated_at')->since(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (BylawDocument $record): string => asset('storage/' . $record->file_path), shouldOpenInNewTab: true),
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
            'index' => Pages\ListBylawDocuments::route('/'),
            'create' => Pages\CreateBylawDocument::route('/create'),
            'edit' => Pages\EditBylawDocument::route('/{record}/edit'),
        ];
    }
}
