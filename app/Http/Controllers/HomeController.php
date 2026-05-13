<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\NewsPost;
use App\Models\StaticPage;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sections = StaticPage::query()
            ->published()
            ->whereIn('slug', [
                'home-welcome-message',
                'home-about-alumni-association',
                'home-vision-mission',
                'home-presidents-message',
            ])
            ->get()
            ->keyBy('slug');

        return view('home.index', [
            'sections' => $sections,
            'featuredAlumni' => Alumni::query()->distinguished()->latest()->take(6)->get(),
            'upcomingEvents' => Event::query()->upcoming()->orderBy('start_at')->take(4)->get(),
            'newsPosts' => NewsPost::query()
                ->published()
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->take(4)
                ->get(),
            'galleryPreview' => GalleryAlbum::query()->published()->latest('published_at')->take(4)->get(),
        ]);
    }
}
