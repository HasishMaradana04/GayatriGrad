<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\AdminRole;

class RoleResource extends AuthorizedResource
{
    protected static ?string $model = AdminRole::class;

    protected static ?string $permissionModule = 'roles';

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'Administration';

    public static function getNavigationLabel(): string
    {
        return 'Roles & Permissions';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->required()->unique(ignoreRecord: true)->maxLength(255),
            TextInput::make('guard_name')->required()->default('web')->maxLength(255),
            CheckboxList::make('permissions')
                ->relationship('permissions', 'name')
                ->columns(2)
                ->searchable()
                ->bulkToggleable()
                ->required()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('permissions_count')->counts('permissions')->label('Permissions')->sortable(),
                TextColumn::make('updated_at')->since()->sortable(),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
