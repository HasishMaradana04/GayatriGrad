<?php

namespace App\Http\Controllers;

use App\Models\GalleryAlbum;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('gallery.index', [
            'albums' => GalleryAlbum::query()->published()->withCount('media')->latest('published_at')->paginate(9),
        ]);
    }

    public function show(GalleryAlbum $gallery): View
    {
        $gallery->load('media');

        return view('gallery.show', [
            'album' => $gallery,
            'relatedAlbums' => GalleryAlbum::query()
                ->published()
                ->whereKeyNot($gallery->id)
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
