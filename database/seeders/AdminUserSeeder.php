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
            ['name' => 'Super Admin', 'email' => 'hasishmaradana@gmail.com', 'role' => 'Super Admin', 'password' => 'hasish@2007'],
            ['name' => 'Events Manager', 'email' => 'events@example.com', 'role' => 'Events Manager', 'password' => 'Password@123'],
            ['name' => 'Gallery Manager', 'email' => 'gallery@example.com', 'role' => 'Gallery Manager', 'password' => 'Password@123'],
            ['name' => 'News Manager', 'email' => 'news@example.com', 'role' => 'News Manager', 'password' => 'Password@123'],
            ['name' => 'Faculty Manager', 'email' => 'faculty@example.com', 'role' => 'Faculty Manager', 'password' => 'Password@123'],
        ];

        foreach ($users as $admin) {
            $user = User::query()->updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make($admin['password']),
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
