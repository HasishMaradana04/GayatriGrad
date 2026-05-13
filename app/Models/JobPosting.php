<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'title',
        'company',
        'location',
        'employment_type',
        'description',
        'apply_url',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
