<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsPostResource\Pages;
use App\Models\NewsPost;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
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

class NewsPostResource extends AuthorizedResource
{
    protected static ?string $model = NewsPost::class;

    protected static ?string $permissionModule = 'news';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Engagement';

    public static function getNavigationLabel(): string
    {
        return 'News & Updates';
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
            Select::make('post_type')
                ->required()
                ->options([
                    'announcement' => 'Announcement',
                    'newsletter' => 'Newsletter',
                    'achievement' => 'Achievement',
                ])
                ->default('announcement'),
            FileUpload::make('cover_image_path')
                ->label('Cover Image')
                ->directory('news')
                ->disk('public')
                ->image()
                ->imageEditor(),
            Textarea::make('excerpt')->rows(3)->columnSpanFull(),
            RichEditor::make('content')->required()->columnSpanFull(),
            Toggle::make('is_published')->default(true),
            DateTimePicker::make('published_at')->seconds(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image_path')->disk('public')->label('Cover'),
                TextColumn::make('title')->searchable()->sortable()->limit(50),
                TextColumn::make('post_type')->badge(),
                TextColumn::make('published_at')->dateTime()->sortable(),
                TextColumn::make('updated_at')->since()->label('Updated'),
            ])
            ->filters([
                SelectFilter::make('post_type')
                    ->options([
                        'announcement' => 'Announcement',
                        'newsletter' => 'Newsletter',
                        'achievement' => 'Achievement',
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
            'index' => Pages\ListNewsPosts::route('/'),
            'create' => Pages\CreateNewsPost::route('/create'),
            'edit' => Pages\EditNewsPost::route('/{record}/edit'),
        ];
    }
}
