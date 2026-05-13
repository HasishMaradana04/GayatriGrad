<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('events.index', [
            'upcomingEvents' => Event::query()->upcoming()->orderBy('start_at')->paginate(6, ['*'], 'upcoming'),
            'pastEvents' => Event::query()->past()->orderByDesc('start_at')->paginate(6, ['*'], 'past'),
        ]);
    }

    public function show(Event $event): View
    {
        $event->load('media');

        return view('events.show', [
            'event' => $event,
            'relatedEvents' => Event::query()
                ->whereKeyNot($event->id)
                ->orderByDesc('start_at')
                ->take(3)
                ->get(),
        ]);
    }
}
