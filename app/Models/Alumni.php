<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use LogsAdminActivity;

    protected $table = 'alumni';

    protected $fillable = [
        'name',
        'graduation_year',
        'degree',
        'department',
        'current_position',
        'organization',
        'location',
        'profile_photo_path',
        'achievements',
        'is_distinguished',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'is_distinguished' => 'boolean',
    ];

    public function scopeDistinguished($query)
    {
        return $query->where('is_distinguished', true);
    }
}
