<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StaticPageResource\Pages;
use App\Models\StaticPage;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StaticPageResource extends AuthorizedResource
{
    protected static ?string $model = StaticPage::class;

    protected static ?string $permissionModule = 'static-pages';

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Content Management';

    public static function getNavigationLabel(): string
    {
        return 'Static Pages';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('slug')
                ->required()
                ->options([
                    'home-welcome-message' => 'Home: Welcome Message',
                    'home-about-alumni-association' => 'Home: About Alumni Association',
                    'home-vision-mission' => 'Home: Vision & Mission',
                    'home-presidents-message' => 'Home: President\'s Message',
                    'about-history' => 'About Us: History',
                    'about-governing-body' => 'About Us: Governing Body / Executive Committee',
                    'about-office-bearers' => 'About Us: Office Bearers',
                    'about-constitution-bylaws' => 'About Us: Constitution / Bylaws',
                    'contributions-intro' => 'Contributions: Intro Text',
                    'career-networking-intro' => 'Career & Networking: Intro Text',
                    'contact-intro' => 'Contact: Intro Text',
                ])
                ->searchable(),
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('sort_order')->numeric()->default(0),
            Toggle::make('is_published')->default(true),
            Textarea::make('excerpt')->rows(3)->columnSpanFull(),
            RichEditor::make('content')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('slug')->searchable()->sortable(),
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('sort_order')->sortable(),
                TextColumn::make('is_published')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Published' : 'Draft'),
                TextColumn::make('updated_at')->since(),
            ])
            ->filters([
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
            'index' => Pages\ListStaticPages::route('/'),
            'create' => Pages\CreateStaticPage::route('/create'),
            'edit' => Pages\EditStaticPage::route('/{record}/edit'),
        ];
    }
}
