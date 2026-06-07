<?php

namespace App\Http\Controllers;

use App\Models\DonationCampaign;
use App\Models\Scholarship;
use App\Models\StaticPage;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ContributionController extends Controller
{
    public function index(): View
    {
        return view('contributions.index', [
            'intro' => Cache::remember('contributions.intro', now()->addHour(), fn () => StaticPage::query()->published()->where('slug', 'contributions-intro')->first()),
            'campaigns' => Cache::remember('contributions.campaigns', now()->addHour(), fn () => DonationCampaign::query()->active()->latest()->get()),
            'scholarships' => Cache::remember('contributions.scholarships', now()->addHour(), fn () => Scholarship::query()->active()->orderBy('deadline')->get()),
        ]);
    }
}
