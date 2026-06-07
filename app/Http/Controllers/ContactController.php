<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('contact.index', [
            'intro' => Cache::remember('contact.intro', now()->addHour(), fn () => StaticPage::query()->published()->where('slug', 'contact-intro')->first()),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::query()->create($validated);

        return back()->with('status', 'Thank you for contacting us. We will get back to you soon.');
    }
}
