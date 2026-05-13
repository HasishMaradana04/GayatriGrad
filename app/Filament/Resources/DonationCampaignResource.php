<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationCampaignResource\Pages;
use App\Models\DonationCampaign;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class DonationCampaignResource extends AuthorizedResource
{
    protected static ?string $model = DonationCampaign::class;

    protected static ?string $permissionModule = 'donations';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Funding';

    public static function getNavigationLabel(): string
    {
        return 'Donations';
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
            Select::make('campaign_type')
                ->required()
                ->options([
                    'donate_support' => 'Donate / Support',
                    'scholarship' => 'Scholarship Fund',
                    'endowment' => 'Endowment Fund',
                ])
                ->default('donate_support'),
            TextInput::make('target_amount')->numeric()->prefix('INR'),
            TextInput::make('raised_amount')->numeric()->prefix('INR')->default(0),
            DatePicker::make('start_date'),
            DatePicker::make('end_date'),
            TextInput::make('donation_url')->url()->maxLength(255),
            Toggle::make('is_active')->default(true),
            TextInput::make('summary')->maxLength(255)->columnSpanFull(),
            RichEditor::make('description')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->limit(45),
                TextColumn::make('campaign_type')->badge(),
                TextColumn::make('target_amount')->money('INR')->sortable(),
                TextColumn::make('raised_amount')->money('INR')->sortable(),
                TextColumn::make('end_date')->date(),
            ])
            ->filters([
                SelectFilter::make('campaign_type')->options([
                    'donate_support' => 'Donate / Support',
                    'scholarship' => 'Scholarship Fund',
                    'endowment' => 'Endowment Fund',
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
            'index' => Pages\ListDonationCampaigns::route('/'),
            'create' => Pages\CreateDonationCampaign::route('/create'),
            'edit' => Pages\EditDonationCampaign::route('/{record}/edit'),
        ];
    }
}
