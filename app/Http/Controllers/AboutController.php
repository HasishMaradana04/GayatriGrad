<?php

namespace App\Http\Controllers;

use App\Models\BylawDocument;
use App\Models\Chapter;
use App\Models\CommitteeMember;
use App\Models\StaticPage;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $sections = StaticPage::query()
            ->published()
            ->whereIn('slug', [
                'about-history',
                'about-governing-body',
                'about-office-bearers',
                'about-constitution-bylaws',
            ])
            ->orderBy('sort_order')
            ->get()
            ->keyBy('slug');

        return view('about.index', [
            'sections' => $sections,
            'governingBody' => CommitteeMember::query()
                ->where('committee_type', 'governing_body')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'executiveCommittee' => CommitteeMember::query()
                ->where('committee_type', 'executive_committee')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'officeBearers' => CommitteeMember::query()
                ->where('committee_type', 'office_bearer')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'chapters' => Chapter::query()
                ->where('is_active', true)
                ->orderBy('chapter_type')
                ->orderBy('name')
                ->get(),
            'bylaws' => BylawDocument::query()
                ->where('is_active', true)
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get(),
        ]);
    }
}
