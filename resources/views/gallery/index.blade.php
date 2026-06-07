@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Gallery</h1>
        <p class="mt-2 text-gray-700">Photo and video albums from alumni activities.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($albums as $album)
            <a href="{{ route('gallery.show', $album) }}" class="overflow-hidden rounded-xl border hover:border-primary/40">
                <img src="{{ $album->cover_image_path ? asset('storage/' . $album->cover_image_path) : asset('images/alumni-logo.jpeg') }}" alt="{{ $album->title }}" width="384" height="192" loading="lazy" decoding="async" class="h-48 w-full object-cover">
                <div class="p-4">
                    <p class="text-xs font-semibold uppercase text-accent">{{ ucfirst($album->album_type) }}</p>
                    <h2 class="text-xl font-semibold">{{ $album->title }}</h2>
                    <p class="text-sm text-gray-600">{{ $album->media_count }} items</p>
                </div>
            </a>
        @empty
            <p class="text-sm text-gray-600">No gallery albums are published yet.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $albums->links() }}</div>
</section>
@endsection
