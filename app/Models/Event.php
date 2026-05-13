<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'event_type',
        'status',
        'location',
        'start_at',
        'end_at',
        'cover_image_path',
        'is_featured',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function media(): HasMany
    {
        return $this->hasMany(EventMedia::class)->orderBy('sort_order');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopePast($query)
    {
        return $query->where('status', 'past');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
