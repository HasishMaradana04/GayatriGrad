<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommitteeMember extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'name',
        'designation',
        'committee_type',
        'chapter_id',
        'tenure_start',
        'tenure_end',
        'email',
        'phone',
        'photo_path',
        'bio',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'tenure_start' => 'date',
        'tenure_end' => 'date',
        'is_active' => 'boolean',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
