<?php

namespace App\Http\Controllers;

use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $query = NewsPost::query()->published()->orderByDesc('published_at');

        if ($request->filled('type')) {
            $query->where('post_type', (string) $request->string('type'));
        }

        return view('news.index', [
            'posts' => $query->paginate(9)->withQueryString(),
        ]);
    }

    public function show(NewsPost $news): View
    {
        return view('news.show', [
            'news' => $news,
            'relatedPosts' => NewsPost::query()
                ->published()
                ->whereKeyNot($news->id)
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
