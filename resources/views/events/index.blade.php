@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Events</h1>
        <p class="mt-2 text-gray-700">Upcoming events, annual meet, and past gatherings.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <h2 class="heading-font text-3xl font-bold text-primary">Upcoming Events</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($upcomingEvents as $event)
            <article class="overflow-hidden rounded-xl border">
                <img src="{{ $event->cover_image_path ? asset('storage/' . $event->cover_image_path) : asset('images/alumni-logo.jpeg') }}" width="320" height="160" loading="lazy" decoding="async" class="h-40 w-full object-cover" alt="{{ $event->title }}">
                <div class="p-4">
                    <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $event->event_type) }}</p>
                    <h3 class="font-semibold">{{ $event->title }}</h3>
                    <p class="text-sm text-gray-600">{{ $event->start_at?->format('d M Y, h:i A') }}</p>
                    <p class="text-sm text-gray-600">{{ $event->location }}</p>
                    <a href="{{ route('events.show', $event) }}" class="mt-3 inline-block text-sm font-semibold text-primary">View Details</a>
                </div>
            </article>
        @empty
            <p class="text-sm text-gray-600">No upcoming events available.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $upcomingEvents->links() }}</div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <h2 class="heading-font text-3xl font-bold text-primary">Past Events</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($pastEvents as $event)
            <article class="rounded-xl border p-4">
                <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $event->event_type) }}</p>
                <h3 class="font-semibold">{{ $event->title }}</h3>
                <p class="text-sm text-gray-600">{{ $event->start_at?->format('d M Y') }} • {{ $event->location }}</p>
                <a href="{{ route('events.show', $event) }}" class="mt-3 inline-block text-sm font-semibold text-primary">View Gallery</a>
            </article>
        @empty
            <p class="text-sm text-gray-600">No past events available.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $pastEvents->links() }}</div>
</section>
@endsection
