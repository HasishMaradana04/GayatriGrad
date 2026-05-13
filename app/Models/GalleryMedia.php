<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GalleryMedia extends Model
{
    use LogsAdminActivity;

    protected $fillable = [
        'gallery_album_id',
        'media_type',
        'file_path',
        'video_url',
        'caption',
        'category',
        'sort_order',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(GalleryAlbum::class, 'gallery_album_id');
    }
}
