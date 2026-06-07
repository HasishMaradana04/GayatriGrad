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
        $permissionsSchema = [];
        
        foreach (\App\Support\AdminPermissions::MODULES as $module => $label) {
            $permissionsSchema[] = \Filament\Forms\Components\Section::make($label)
                ->schema([
                    \Filament\Forms\Components\CheckboxList::make("permissions_{$module}")
                        ->hiddenLabel()
                        ->options(function () use ($module) {
                            $permissions = \App\Support\AdminPermissions::modulePermissions($module);
                            return collect($permissions)->mapWithKeys(fn($p) => [$p => $p])->toArray();
                        })
                        ->columns(2)
                        ->bulkToggleable()
                        ->loadStateFromRelationshipsUsing(function ($component, $record) use ($module) {
                            if (!$record) {
                                $component->state([]);
                                return;
                            }
                            $modulePerms = \App\Support\AdminPermissions::modulePermissions($module);
                            $state = $record->permissions->whereIn('name', $modulePerms)->pluck('name')->toArray();
                            $component->state($state);
                        })
                        ->saveRelationshipsUsing(function ($component, $state, $record) use ($module) {
                            $modulePerms = \App\Support\AdminPermissions::modulePermissions($module);
                            $currentPerms = $record->permissions->pluck('name')->toArray();
                            
                            $toRemove = array_diff($modulePerms, $state ?? []);
                            foreach ($toRemove as $perm) {
                                if (in_array($perm, $currentPerms)) {
                                    $record->revokePermissionTo($perm);
                                }
                            }
                            
                            if (!empty($state)) {
                                $record->givePermissionTo($state);
                            }
                        })
                        ->dehydrated(false)
                ])
                ->collapsible()
                ->collapsed(true);
        }

        return $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255)
                ->disabled(fn (?AdminRole $record): bool => $record && $record->name === 'Super Admin'),
            TextInput::make('guard_name')
                ->required()
                ->default('web')
                ->maxLength(255)
                ->disabled(fn (?AdminRole $record): bool => $record && $record->name === 'Super Admin'),
            \Filament\Forms\Components\Section::make('Permissions')
                ->description('Select the permissions for this role. (Grouped by module)')
                ->schema($permissionsSchema)
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
                \Filament\Tables\Actions\DeleteAction::make()
                    ->hidden(fn (?AdminRole $record): bool => $record && $record->name === 'Super Admin'),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make()
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->each(function ($record) {
                                if ($record->name !== 'Super Admin') {
                                    $record->delete();
                                }
                            });
                        }),
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
