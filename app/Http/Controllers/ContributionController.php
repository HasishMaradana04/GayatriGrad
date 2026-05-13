<?php

namespace App\Http\Controllers;

use App\Models\DonationCampaign;
use App\Models\Scholarship;
use App\Models\StaticPage;
use Illuminate\View\View;

class ContributionController extends Controller
{
    public function index(): View
    {
        return view('contributions.index', [
            'intro' => StaticPage::query()->published()->where('slug', 'contributions-intro')->first(),
            'campaigns' => DonationCampaign::query()->active()->latest()->get(),
            'scholarships' => Scholarship::query()->active()->orderBy('deadline')->get(),
        ]);
    }
}
