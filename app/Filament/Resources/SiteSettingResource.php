<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SiteSettingResource extends AuthorizedResource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $permissionModule = 'site-settings';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Content Management';

    public static function getNavigationLabel(): string
    {
        return 'Site Settings';
    }

    public static function canCreate(): bool
    {
        return parent::canCreate() && ! SiteSetting::query()->exists();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Branding')
                ->schema([
                    TextInput::make('organization_name')->maxLength(255),
                    TextInput::make('short_name')->maxLength(255),
                    FileUpload::make('logo_path')
                        ->label('Logo')
                        ->directory('branding')
                        ->disk('public')
                        ->image()
                        ->imageEditor(),
                    TextInput::make('hero_title')->maxLength(255),
                    Textarea::make('hero_subtitle')->rows(3)->columnSpanFull(),
                    FileUpload::make('hero_background_image')
                        ->directory('branding')
                        ->disk('public')
                        ->image()
                        ->imageEditor(),
                ])->columns(2),
            Section::make('Registration Links')
                ->schema([
                    TextInput::make('registration_portal_url')->url()->maxLength(255),
                    TextInput::make('registration_new_alumni_url')->url()->maxLength(255),
                    TextInput::make('registration_update_profile_url')->url()->maxLength(255),
                    TextInput::make('registration_membership_details_url')->url()->maxLength(255),
                    TextInput::make('registration_login_url')->url()->maxLength(255),
                ])->columns(2),
            Section::make('Contact & Social')
                ->schema([
                    Textarea::make('contact_address')->rows(3)->columnSpanFull(),
                    TextInput::make('contact_phone_primary')->maxLength(255),
                    TextInput::make('contact_phone_secondary')->maxLength(255),
                    TextInput::make('contact_email')->email()->maxLength(255),
                    TextInput::make('contact_map_embed_url')->url()->maxLength(255),
                    TextInput::make('facebook_url')->url()->maxLength(255),
                    TextInput::make('linkedin_url')->url()->maxLength(255),
                    TextInput::make('instagram_url')->url()->maxLength(255),
                    TextInput::make('x_url')->url()->maxLength(255),
                    TextInput::make('youtube_url')->url()->maxLength(255),
                    Textarea::make('footer_text')->rows(2)->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('organization_name')->searchable(),
                TextColumn::make('contact_email'),
                TextColumn::make('updated_at')->since(),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
