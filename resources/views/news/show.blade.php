@extends('layouts.app')

@section('content')
<article class="mx-auto max-w-4xl px-4 py-12">
    <p class="text-sm font-semibold uppercase text-accent">{{ str_replace('_', ' ', $news->post_type) }}</p>
    <h1 class="heading-font mt-1 text-4xl font-black text-primary">{{ $news->title }}</h1>
    <p class="mt-2 text-sm text-gray-600">{{ $news->published_at?->format('d M Y, h:i A') }}</p>
    @if($news->cover_image_path)
        <img src="{{ asset('storage/' . $news->cover_image_path) }}" class="mt-6 h-80 w-full rounded-xl object-cover" alt="{{ $news->title }}">
    @endif
    <div class="prose mt-6 max-w-none">{!! $news->content !!}</div>
</article>

<section class="mx-auto max-w-4xl px-4 pb-14">
    <h2 class="heading-font text-2xl font-bold text-primary">Related Posts</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-3">
        @foreach($relatedPosts as $post)
            <a href="{{ route('news.show', $post) }}" class="rounded-xl border p-4 hover:border-primary/40">
                <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $post->post_type) }}</p>
                <p class="font-semibold">{{ $post->title }}</p>
            </a>
        @endforeach
    </div>
</section>
@endsection
