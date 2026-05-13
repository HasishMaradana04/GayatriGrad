<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class EventResource extends AuthorizedResource
{
    protected static ?string $model = Event::class;

    protected static ?string $permissionModule = 'events';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Engagement';

    public static function getNavigationLabel(): string
    {
        return 'Events';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
            TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
            Select::make('event_type')
                ->required()
                ->options([
                    'general' => 'General Event',
                    'annual_meet' => 'Annual Meet',
                ])
                ->default('general'),
            Select::make('status')
                ->required()
                ->options([
                    'upcoming' => 'Upcoming',
                    'past' => 'Past',
                ])
                ->default('upcoming'),
            TextInput::make('location')->maxLength(255),
            DateTimePicker::make('start_at')->required()->seconds(false),
            DateTimePicker::make('end_at')->seconds(false),
            Toggle::make('is_featured'),
            FileUpload::make('cover_image_path')
                ->label('Cover Image')
                ->directory('events/covers')
                ->disk('public')
                ->image()
                ->imageEditor(),
            RichEditor::make('description')->columnSpanFull(),
            Repeater::make('media')
                ->relationship()
                ->label('Event Gallery')
                ->schema([
                    Select::make('media_type')
                        ->options([
                            'photo' => 'Photo',
                            'video' => 'Video',
                        ])
                        ->default('photo')
                        ->required(),
                    FileUpload::make('file_path')
                        ->label('Media File')
                        ->directory('events/media')
                        ->disk('public')
                        ->openable()
                        ->downloadable(),
                    TextInput::make('video_url')
                        ->url()
                        ->maxLength(255)
                        ->helperText('Use this for externally hosted videos.'),
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
            ->defaultSort('start_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image_path')->disk('public')->label('Cover'),
                TextColumn::make('title')->searchable()->sortable()->limit(40),
                TextColumn::make('status')->badge(),
                TextColumn::make('event_type')->badge(),
                TextColumn::make('location')->searchable(),
                TextColumn::make('start_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'upcoming' => 'Upcoming',
                        'past' => 'Past',
                    ]),
                SelectFilter::make('event_type')
                    ->options([
                        'general' => 'General Event',
                        'annual_meet' => 'Annual Meet',
                    ]),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
