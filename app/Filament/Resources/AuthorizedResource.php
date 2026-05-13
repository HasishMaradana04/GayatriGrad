<?php

namespace App\Filament\Resources;

use App\Support\AdminPermissions;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

abstract class AuthorizedResource extends Resource
{
    protected static ?string $permissionModule = null;

    public static function shouldRegisterNavigation(): bool
    {
        return static::canViewAny();
    }

    public static function canViewAny(): bool
    {
        return static::canPermission('view');
    }

    public static function canCreate(): bool
    {
        return static::canPermission('create');
    }

    public static function canEdit(Model $record): bool
    {
        return static::canPermission('update');
    }

    public static function canDelete(Model $record): bool
    {
        return static::canPermission('delete');
    }

    public static function canDeleteAny(): bool
    {
        return static::canPermission('delete');
    }

    public static function canForceDelete(Model $record): bool
    {
        return static::canPermission('delete');
    }

    public static function canForceDeleteAny(): bool
    {
        return static::canPermission('delete');
    }

    public static function canRestore(Model $record): bool
    {
        return static::canPermission('update');
    }

    public static function canRestoreAny(): bool
    {
        return static::canPermission('update');
    }

    protected static function canPermission(string $action): bool
    {
        $user = auth()->user();
        $module = static::$permissionModule;

        if (! $user || ! $module) {
            return false;
        }

        return $user->can(AdminPermissions::name($action, $module));
    }
}
