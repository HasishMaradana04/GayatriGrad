@extends('layouts.app')

@section('content')
<section class="mx-auto max-w-7xl px-4 py-12">
    <p class="text-xs font-semibold uppercase text-accent">{{ ucfirst($album->album_type) }}</p>
    <h1 class="heading-font mt-1 text-4xl font-black text-primary">{{ $album->title }}</h1>
    <p class="mt-2 text-gray-700">{{ $album->description }}</p>
</section>

<section class="mx-auto max-w-7xl px-4 pb-8">
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($album->media as $item)
            <article class="overflow-hidden rounded-xl border">
                @if($item->media_type === 'photo' && $item->file_path)
                    <img src="{{ asset('storage/' . $item->file_path) }}" class="h-52 w-full object-cover" alt="{{ $item->caption }}">
                @elseif($item->media_type === 'video' && $item->video_url)
                    <iframe src="{{ $item->video_url }}" class="h-52 w-full" loading="lazy"></iframe>
                @endif
                <div class="p-3 text-sm text-gray-700">{{ $item->caption }}</div>
            </article>
        @empty
            <p class="text-sm text-gray-600">No media items in this album yet.</p>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <h2 class="heading-font text-2xl font-bold text-primary">Other Albums</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-3">
        @foreach($relatedAlbums as $item)
            <a href="{{ route('gallery.show', $item) }}" class="rounded-xl border p-4 hover:border-primary/40">
                <p class="font-semibold">{{ $item->title }}</p>
                <p class="text-sm text-gray-600">{{ ucfirst($item->album_type) }}</p>
            </a>
        @endforeach
    </div>
</section>
@endsection
