<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\Event;
use App\Models\GalleryAlbum;
use App\Models\NewsPost;
use App\Models\StaticPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $sections = Cache::remember('home.sections', now()->addHour(), fn () => StaticPage::query()
            ->published()
            ->whereIn('slug', [
                'home-welcome-message',
                'home-about-alumni-association',
                'home-vision-mission',
                'home-presidents-message',
            ])
            ->get()
            ->keyBy('slug'));

        return view('home.index', [
            'sections' => $sections,
            'featuredAlumni' => Cache::remember('home.featured-alumni', now()->addHour(), fn () => Alumni::query()->distinguished()->latest()->take(6)->get()),
            'upcomingEvents' => Cache::remember('home.upcoming-events', now()->addHour(), fn () => Event::query()->upcoming()->orderBy('start_at')->take(4)->get()),
            'newsPosts' => Cache::remember('home.news-posts', now()->addHour(), fn () => NewsPost::query()
                ->published()
                ->whereNotNull('published_at')
                ->orderByDesc('published_at')
                ->take(4)
                ->get()),
            'galleryPreview' => Cache::remember('home.gallery-preview', now()->addHour(), fn () => GalleryAlbum::query()->published()->latest('published_at')->take(4)->get()),
        ]);
    }
}
