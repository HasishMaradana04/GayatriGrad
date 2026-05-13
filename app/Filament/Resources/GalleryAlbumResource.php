<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryAlbumResource\Pages;
use App\Models\GalleryAlbum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class GalleryAlbumResource extends AuthorizedResource
{
    protected static ?string $model = GalleryAlbum::class;

    protected static ?string $permissionModule = 'gallery';

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Engagement';

    public static function getNavigationLabel(): string
    {
        return 'Gallery';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Select::make('album_type')
                ->required()
                ->options([
                    'photo' => 'Photo',
                    'video' => 'Video',
                    'mixed' => 'Mixed',
                ])
                ->default('mixed'),
            FileUpload::make('cover_image_path')
                ->label('Cover Image')
                ->directory('gallery/albums')
                ->disk('public')
                ->image()
                ->imageEditor(),
            Textarea::make('description')->rows(3)->columnSpanFull(),
            Toggle::make('is_published')->default(true),
            DateTimePicker::make('published_at')->seconds(false),
            Repeater::make('media')
                ->relationship()
                ->label('Album Media')
                ->schema([
                    Select::make('media_type')
                        ->required()
                        ->options([
                            'photo' => 'Photo',
                            'video' => 'Video',
                        ])
                        ->default('photo'),
                    FileUpload::make('file_path')
                        ->label('Media File')
                        ->directory('gallery/media')
                        ->disk('public')
                        ->openable()
                        ->downloadable(),
                    TextInput::make('video_url')->url()->maxLength(255),
                    TextInput::make('category')->maxLength(255),
                    TextInput::make('caption')->maxLength(255),
                    TextInput::make('sort_order')->numeric()->default(0),
                ])
                ->columns(2)
                ->defaultItems(0)
                ->collapsed()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image_path')->disk('public')->label('Cover'),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('album_type')->badge(),
                TextColumn::make('media_count')->counts('media')->label('Items'),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('album_type')
                    ->options([
                        'photo' => 'Photo',
                        'video' => 'Video',
                        'mixed' => 'Mixed',
                    ]),
                TernaryFilter::make('is_published'),
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
            'index' => Pages\ListGalleryAlbums::route('/'),
            'create' => Pages\CreateGalleryAlbum::route('/create'),
            'edit' => Pages\EditGalleryAlbum::route('/{record}/edit'),
        ];
    }
}
