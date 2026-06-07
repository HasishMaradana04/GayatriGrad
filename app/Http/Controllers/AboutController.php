<?php

namespace App\Http\Controllers;

use App\Models\BylawDocument;
use App\Models\Chapter;
use App\Models\CommitteeMember;
use App\Models\StaticPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $sections = Cache::remember('about.sections', now()->addHour(), fn () => StaticPage::query()
            ->published()
            ->whereIn('slug', [
                'about-history',
                'about-governing-body',
                'about-office-bearers',
                'about-constitution-bylaws',
            ])
            ->orderBy('sort_order')
            ->get()
            ->keyBy('slug'));

        return view('about.index', [
            'sections' => $sections,
            'governingBody' => Cache::remember('about.governing-body', now()->addHour(), fn () => CommitteeMember::query()
                ->where('committee_type', 'governing_body')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()),
            'executiveCommittee' => Cache::remember('about.executive-committee', now()->addHour(), fn () => CommitteeMember::query()
                ->where('committee_type', 'executive_committee')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()),
            'officeBearers' => Cache::remember('about.office-bearers', now()->addHour(), fn () => CommitteeMember::query()
                ->where('committee_type', 'office_bearer')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()),
            'chapters' => Cache::remember('about.chapters', now()->addHour(), fn () => Chapter::query()
                ->where('is_active', true)
                ->orderBy('chapter_type')
                ->orderBy('name')
                ->get()),
            'bylaws' => Cache::remember('about.bylaws', now()->addHour(), fn () => BylawDocument::query()
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get()),
        ]);
    }
}
