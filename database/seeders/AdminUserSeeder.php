<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@example.com', 'role' => 'Super Admin'],
            ['name' => 'Events Manager', 'email' => 'events@example.com', 'role' => 'Events Manager'],
            ['name' => 'Gallery Manager', 'email' => 'gallery@example.com', 'role' => 'Gallery Manager'],
            ['name' => 'News Manager', 'email' => 'news@example.com', 'role' => 'News Manager'],
            ['name' => 'Faculty Manager', 'email' => 'faculty@example.com', 'role' => 'Faculty Manager'],
        ];

        foreach ($users as $admin) {
            $user = User::query()->updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('Password@123'),
                    'is_active' => true,
                ],
            );

            $user->syncRoles([$admin['role']]);
        }

        User::query()
            ->whereDoesntHave('roles')
            ->get()
            ->each(fn (User $user) => $user->assignRole('Super Admin'));
    }
}
