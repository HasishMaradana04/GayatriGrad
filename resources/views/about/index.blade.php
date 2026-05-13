@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">About Us</h1>
    </div>
</section>

<section class="mx-auto max-w-7xl space-y-8 px-4 py-10">
    @foreach ([
        'about-history' => 'History of the Alumni Association',
        'about-governing-body' => 'Governing Body / Executive Committee',
        'about-office-bearers' => 'Office Bearers',
        'about-constitution-bylaws' => 'Constitution / Bylaws',
    ] as $key => $fallback)
        @php($page = $sections->get($key))
        <article class="rounded-xl border p-6">
            <h2 class="heading-font text-2xl font-bold text-primary">{{ $page?->title ?: $fallback }}</h2>
            <div class="prose mt-3 max-w-none">{!! $page?->content !!}</div>
        </article>
    @endforeach
</section>

<section class="mx-auto max-w-7xl px-4 pb-10">
    <h2 class="heading-font text-3xl font-bold text-primary">Committee Members</h2>
    <div class="mt-4 grid gap-6 lg:grid-cols-3">
        <div>
            <h3 class="font-bold text-accent">Governing Body</h3>
            <ul class="mt-2 space-y-2">
                @forelse($governingBody as $member)
                    <li class="rounded border p-3">{{ $member->name }} <span class="text-sm text-gray-600">({{ $member->designation }})</span></li>
                @empty
                    <li class="text-sm text-gray-600">No members added yet.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="font-bold text-accent">Executive Committee</h3>
            <ul class="mt-2 space-y-2">
                @forelse($executiveCommittee as $member)
                    <li class="rounded border p-3">{{ $member->name }} <span class="text-sm text-gray-600">({{ $member->designation }})</span></li>
                @empty
                    <li class="text-sm text-gray-600">No members added yet.</li>
                @endforelse
            </ul>
        </div>
        <div>
            <h3 class="font-bold text-accent">Office Bearers</h3>
            <ul class="mt-2 space-y-2">
                @forelse($officeBearers as $member)
                    <li class="rounded border p-3">{{ $member->name }} <span class="text-sm text-gray-600">({{ $member->designation }})</span></li>
                @empty
                    <li class="text-sm text-gray-600">No members added yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-10">
    <h2 class="heading-font text-3xl font-bold text-primary">Chapters</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-2">
        @forelse($chapters as $chapter)
            <article class="rounded-xl border p-5">
                <p class="text-xs font-semibold uppercase text-accent">{{ $chapter->chapter_type }}</p>
                <h3 class="text-xl font-semibold">{{ $chapter->name }}</h3>
                <p class="text-sm text-gray-600">{{ $chapter->location }}</p>
                <p class="mt-2 text-sm">{{ $chapter->description }}</p>
            </article>
        @empty
            <p class="text-sm text-gray-600">No chapters published yet.</p>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <h2 class="heading-font text-3xl font-bold text-primary">Bylaws Documents</h2>
    <ul class="mt-4 space-y-2">
        @forelse($bylaws as $doc)
            <li>
                <a class="rounded border px-4 py-3 inline-block hover:border-primary/40" href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" rel="noopener">
                    {{ $doc->title }}
                </a>
            </li>
        @empty
            <li class="text-sm text-gray-600">No bylaws uploaded yet.</li>
        @endforelse
    </ul>
</section>
@endsection
