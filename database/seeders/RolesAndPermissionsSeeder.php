<?php

namespace Database\Seeders;

use App\Support\AdminPermissions;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (AdminPermissions::all() as $permission) {
            AdminPermission::findOrCreate($permission, 'web');
        }

        AdminRole::findOrCreate('Super Admin', 'web')
            ->syncPermissions(AdminPermissions::all());

        AdminRole::findOrCreate('Events Manager', 'web')
            ->syncPermissions(array_merge(['view dashboard'], AdminPermissions::modulePermissions('events')));

        AdminRole::findOrCreate('Gallery Manager', 'web')
            ->syncPermissions(array_merge(['view dashboard'], AdminPermissions::modulePermissions('gallery')));

        AdminRole::findOrCreate('News Manager', 'web')
            ->syncPermissions(array_merge(['view dashboard'], AdminPermissions::modulePermissions('news')));

        AdminRole::findOrCreate('Faculty Manager', 'web')
            ->syncPermissions(array_merge(
                ['view dashboard'],
                AdminPermissions::modulePermissions('committee-members'),
                AdminPermissions::modulePermissions('chapters')
            ));

        AdminRole::findOrCreate('Content Manager', 'web')
            ->syncPermissions(array_merge(
                ['view dashboard'],
                AdminPermissions::modulePermissions('static-pages'),
                AdminPermissions::modulePermissions('site-settings'),
                AdminPermissions::modulePermissions('bylaws'),
                AdminPermissions::modulePermissions('contact-messages')
            ));

        AdminRole::findOrCreate('Career Manager', 'web')
            ->syncPermissions(array_merge(
                ['view dashboard'],
                AdminPermissions::modulePermissions('alumni'),
                AdminPermissions::modulePermissions('jobs'),
                AdminPermissions::modulePermissions('mentorship')
            ));

        AdminRole::findOrCreate('Funding Manager', 'web')
            ->syncPermissions(array_merge(
                ['view dashboard'],
                AdminPermissions::modulePermissions('donations'),
                AdminPermissions::modulePermissions('scholarships')
            ));

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
