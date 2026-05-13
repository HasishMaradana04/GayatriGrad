<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function index(): View
    {
        try {
            $siteSetting = SiteSetting::current();
        } catch (\Throwable) {
            $siteSetting = null;
        }

        $links = [
            [
                'title' => 'Main Registration Portal',
                'description' => 'Central entry point for alumni registration services.',
                'url' => $siteSetting?->registration_portal_url,
            ],
            [
                'title' => 'New Alumni Registration',
                'description' => 'Create a new alumni profile.',
                'url' => $siteSetting?->registration_new_alumni_url,
            ],
            [
                'title' => 'Update Profile',
                'description' => 'Update existing alumni details.',
                'url' => $siteSetting?->registration_update_profile_url,
            ],
            [
                'title' => 'Membership Details',
                'description' => 'Review membership plans and eligibility.',
                'url' => $siteSetting?->registration_membership_details_url,
            ],
            [
                'title' => 'Login',
                'description' => 'Access the existing registration account.',
                'url' => $siteSetting?->registration_login_url,
            ],
        ];

        $hasAnyLink = collect($links)->contains(fn (array $link): bool => filled($link['url']));

        return view('registration.index', [
            'links' => $links,
            'hasAnyLink' => $hasAnyLink,
        ]);
    }
}
