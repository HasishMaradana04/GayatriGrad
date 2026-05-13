@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-5xl px-4 py-10">
    <p class="text-sm font-semibold uppercase text-accent">{{ str_replace('_', ' ', $event->event_type) }}</p>
    <h1 class="heading-font mt-1 text-4xl font-black text-primary">{{ $event->title }}</h1>
    <p class="mt-2 text-gray-700">{{ $event->start_at?->format('d M Y, h:i A') }} • {{ $event->location }}</p>
    @if($event->cover_image_path)
        <img src="{{ asset('storage/' . $event->cover_image_path) }}" alt="{{ $event->title }}" class="mt-6 h-80 w-full rounded-xl object-cover">
    @endif
    <div class="prose mt-6 max-w-none">{!! $event->description !!}</div>
</section>

<section class="mx-auto max-w-5xl px-4 pb-10">
    <h2 class="heading-font text-2xl font-bold text-primary">Event Gallery</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($event->media as $item)
            <article class="overflow-hidden rounded-xl border">
                @if($item->media_type === 'photo' && $item->file_path)
                    <img src="{{ asset('storage/' . $item->file_path) }}" class="h-44 w-full object-cover" alt="{{ $item->caption }}">
                @elseif($item->media_type === 'video' && $item->video_url)
                    <iframe src="{{ $item->video_url }}" class="h-44 w-full" loading="lazy"></iframe>
                @endif
                <div class="p-3 text-sm">{{ $item->caption }}</div>
            </article>
        @empty
            <p class="text-sm text-gray-600">No media uploaded for this event yet.</p>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-5xl px-4 pb-14">
    <h2 class="heading-font text-2xl font-bold text-primary">Related Events</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-3">
        @foreach($relatedEvents as $item)
            <a href="{{ route('events.show', $item) }}" class="rounded-xl border p-4 hover:border-primary/40">
                <p class="font-semibold">{{ $item->title }}</p>
                <p class="text-sm text-gray-600">{{ $item->start_at?->format('d M Y') }}</p>
            </a>
        @endforeach
    </div>
</section>
@endsection
