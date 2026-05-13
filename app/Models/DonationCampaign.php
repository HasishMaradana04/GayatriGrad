<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;

class DonationCampaign extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'title',
        'slug',
        'campaign_type',
        'summary',
        'description',
        'target_amount',
        'raised_amount',
        'start_date',
        'end_date',
        'donation_url',
        'is_active',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
