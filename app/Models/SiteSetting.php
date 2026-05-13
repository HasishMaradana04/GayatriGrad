<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'organization_name',
        'short_name',
        'logo_path',
        'hero_title',
        'hero_subtitle',
        'hero_background_image',
        'registration_portal_url',
        'registration_new_alumni_url',
        'registration_update_profile_url',
        'registration_membership_details_url',
        'registration_login_url',
        'contact_address',
        'contact_phone_primary',
        'contact_phone_secondary',
        'contact_email',
        'contact_map_embed_url',
        'facebook_url',
        'linkedin_url',
        'instagram_url',
        'x_url',
        'youtube_url',
        'footer_text',
    ];

    public static function current(): ?self
    {
        return static::query()->first();
    }
}
