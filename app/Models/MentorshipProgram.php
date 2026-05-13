<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;

class MentorshipProgram extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'title',
        'mentor_name',
        'mentor_designation',
        'organization',
        'area_of_expertise',
        'contact_email',
        'description',
        'availability',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
