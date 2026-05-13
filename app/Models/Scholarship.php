<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'description',
        'eligibility',
        'application_url',
        'deadline',
        'is_active',
    ];

    protected $casts = [
        'deadline' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
