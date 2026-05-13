<?php

namespace App\Support;

final class AdminPermissions
{
    public const ACTIONS = ['view', 'create', 'update', 'delete'];

    public const MODULES = [
        'dashboard' => 'Dashboard',
        'users' => 'Admin Users',
        'roles' => 'Roles & Permissions',
        'activity' => 'Activity Log',
        'site-settings' => 'Site Settings',
        'static-pages' => 'Static Pages',
        'alumni' => 'Alumni Directory',
        'events' => 'Events',
        'gallery' => 'Gallery',
        'news' => 'News & Updates',
        'committee-members' => 'Committee Members',
        'chapters' => 'Chapters',
        'bylaws' => 'Bylaws',
        'donations' => 'Donations',
        'scholarships' => 'Scholarships',
        'jobs' => 'Jobs',
        'mentorship' => 'Mentorship Programs',
        'contact-messages' => 'Contact Messages',
    ];

    public static function name(string $action, string $module): string
    {
        return "{$action} {$module}";
    }

    public static function modulePermissions(string $module): array
    {
        return array_map(fn (string $action): string => self::name($action, $module), self::ACTIONS);
    }

    public static function all(): array
    {
        $permissions = [];

        foreach (array_keys(self::MODULES) as $module) {
            $permissions = array_merge($permissions, self::modulePermissions($module));
        }

        return array_values(array_unique($permissions));
    }
}
