@extends('layouts.app')

@section('content')
<section class="relative overflow-hidden bg-soft">
    @if($siteSetting?->hero_background_image)
        <img src="{{ asset('storage/' . $siteSetting->hero_background_image) }}" alt="Hero" class="absolute inset-0 h-full w-full object-cover opacity-20">
    @endif
    <div class="relative mx-auto max-w-7xl px-4 py-20">
        <h1 class="heading-font max-w-4xl text-4xl font-black text-primary md:text-6xl">
            {{ $siteSetting?->hero_title ?: 'Welcome to the Alumni Association' }}
        </h1>
        <p class="mt-4 max-w-3xl text-lg text-gray-700">
            {{ $siteSetting?->hero_subtitle ?: 'Build connections, celebrate achievements, and support future generations.' }}
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('alumni.index') }}" class="rounded-md bg-primary px-6 py-3 text-white">Explore Directory</a>
            <a href="{{ route('events.index') }}" class="rounded-md border border-primary px-6 py-3 text-primary">View Events</a>
        </div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-6 px-4 py-12 md:grid-cols-2">
    @foreach ([
        'home-welcome-message' => 'Welcome Message',
        'home-about-alumni-association' => 'About Alumni Association',
        'home-vision-mission' => 'Vision & Mission',
        'home-presidents-message' => 'President\'s Message',
    ] as $key => $label)
        @php($page = $sections->get($key))
        <article class="rounded-xl border bg-white p-6 shadow-sm">
            <h2 class="heading-font text-2xl font-bold text-primary">{{ $page?->title ?: $label }}</h2>
            <div class="prose mt-3 max-w-none text-gray-700">
                {!! $page?->content !!}
            </div>
        </article>
    @endforeach
</section>

<section class="mx-auto max-w-7xl px-4 py-6">
    <div class="mb-5 flex items-center justify-between">
        <h2 class="heading-font text-3xl font-bold text-primary">Featured Alumni</h2>
        <a href="{{ route('alumni.index') }}" class="text-sm font-semibold text-accent">View all</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($featuredAlumni as $person)
            <article class="rounded-xl border p-4">
                <div class="flex items-center gap-4">
                    <img src="{{ $person->profile_photo_path ? asset('storage/' . $person->profile_photo_path) : asset('images/alumni-logo.jpeg') }}" class="h-14 w-14 rounded-full object-cover" alt="{{ $person->name }}">
                    <div>
                        <h3 class="font-semibold">{{ $person->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $person->department }} • {{ $person->graduation_year }}</p>
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-700">{{ $person->current_position }}{{ $person->organization ? ' at ' . $person->organization : '' }}</p>
            </article>
        @empty
            <p class="text-gray-600">No featured alumni published yet.</p>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-2xl bg-primary p-6 text-white">
            <h2 class="heading-font text-3xl font-bold">Upcoming Events</h2>
            <div class="mt-4 space-y-3">
                @forelse($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event) }}" class="block rounded-lg border border-white/20 p-3 hover:bg-white/10">
                        <p class="font-semibold">{{ $event->title }}</p>
                        <p class="text-sm">{{ $event->start_at?->format('d M Y, h:i A') }} • {{ $event->location }}</p>
                    </a>
                @empty
                    <p class="text-sm text-white/90">No upcoming events yet.</p>
                @endforelse
            </div>
        </div>
        <div>
            <h2 class="heading-font text-3xl font-bold text-primary">News Highlights</h2>
            <div class="mt-4 space-y-3">
                @forelse($newsPosts as $post)
                    <a href="{{ route('news.show', $post) }}" class="block rounded-lg border p-4 hover:border-primary/40">
                        <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $post->post_type) }}</p>
                        <p class="font-semibold">{{ $post->title }}</p>
                        <p class="text-sm text-gray-600">{{ $post->published_at?->format('d M Y') }}</p>
                    </a>
                @empty
                    <p class="text-sm text-gray-600">No news posts yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-12">
    <div class="mb-5 flex items-center justify-between">
        <h2 class="heading-font text-3xl font-bold text-primary">Gallery Preview</h2>
        <a href="{{ route('gallery.index') }}" class="text-sm font-semibold text-accent">Open gallery</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($galleryPreview as $album)
            <a href="{{ route('gallery.show', $album) }}" class="overflow-hidden rounded-xl border">
                <img src="{{ $album->cover_image_path ? asset('storage/' . $album->cover_image_path) : asset('images/alumni-logo.jpeg') }}" class="h-40 w-full object-cover" alt="{{ $album->title }}">
                <div class="p-3">
                    <p class="font-semibold">{{ $album->title }}</p>
                    <p class="text-sm text-gray-600">{{ ucfirst($album->album_type) }} album</p>
                </div>
            </a>
        @empty
            <p class="text-sm text-gray-600">No albums published yet.</p>
        @endforelse
    </div>
</section>
@endsection
