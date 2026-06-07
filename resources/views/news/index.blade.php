@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">News & Updates</h1>
        <p class="mt-2 text-gray-700">Announcements, newsletters, and alumni achievements.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-8">
    <form class="flex flex-wrap gap-2">
        <a href="{{ route('news.index') }}" class="rounded-full border px-4 py-2 text-sm {{ request('type') ? '' : 'bg-primary text-white' }}">All</a>
        <a href="{{ route('news.index', ['type' => 'announcement']) }}" class="rounded-full border px-4 py-2 text-sm {{ request('type') === 'announcement' ? 'bg-primary text-white' : '' }}">Announcements</a>
        <a href="{{ route('news.index', ['type' => 'newsletter']) }}" class="rounded-full border px-4 py-2 text-sm {{ request('type') === 'newsletter' ? 'bg-primary text-white' : '' }}">Newsletters</a>
        <a href="{{ route('news.index', ['type' => 'achievement']) }}" class="rounded-full border px-4 py-2 text-sm {{ request('type') === 'achievement' ? 'bg-primary text-white' : '' }}">Achievements</a>
    </form>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($posts as $post)
            <article class="overflow-hidden rounded-xl border">
                <img src="{{ $post->cover_image_path ? asset('storage/' . $post->cover_image_path) : asset('images/alumni-logo.jpeg') }}" alt="{{ $post->title }}" width="384" height="176" loading="lazy" decoding="async" class="h-44 w-full object-cover">
                <div class="p-4">
                    <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $post->post_type) }}</p>
                    <h2 class="mt-1 text-xl font-semibold">{{ $post->title }}</h2>
                    <p class="mt-2 text-sm text-gray-600">{{ $post->published_at?->format('d M Y') }}</p>
                    <p class="mt-2 text-sm text-gray-700">{{ $post->excerpt }}</p>
                    <a href="{{ route('news.show', $post) }}" class="mt-3 inline-block text-sm font-semibold text-primary">Read More</a>
                </div>
            </article>
        @empty
            <p class="text-sm text-gray-600">No posts available.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $posts->links() }}</div>
</section>
@endsection
