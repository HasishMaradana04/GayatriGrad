<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\MentorshipProgram;
use App\Models\StaticPage;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        return view('career.index', [
            'intro' => StaticPage::query()->published()->where('slug', 'career-networking-intro')->first(),
            'jobs' => JobPosting::query()
                ->active()
                ->where(function ($query): void {
                    $query->whereNull('expires_at')->orWhereDate('expires_at', '>=', now());
                })
                ->latest()
                ->paginate(8, ['*'], 'jobs'),
            'mentorshipPrograms' => MentorshipProgram::query()->active()->latest()->paginate(8, ['*'], 'mentorship'),
        ]);
    }
}
