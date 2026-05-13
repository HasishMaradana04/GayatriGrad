<?php

namespace App\Policies;

use App\Models\User;
use App\Support\AdminPermissions;
use Illuminate\Database\Eloquent\Model;

abstract class BaseModulePolicy
{
    protected string $module;

    public function before(User $user): ?bool
    {
        return $user->hasRole('Super Admin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can(AdminPermissions::name('view', $this->module));
    }

    public function view(User $user, Model $model): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can(AdminPermissions::name('create', $this->module));
    }

    public function update(User $user, Model $model): bool
    {
        return $user->can(AdminPermissions::name('update', $this->module));
    }

    public function delete(User $user, Model $model): bool
    {
        return $user->can(AdminPermissions::name('delete', $this->module));
    }

    public function deleteAny(User $user): bool
    {
        return $user->can(AdminPermissions::name('delete', $this->module));
    }
}
