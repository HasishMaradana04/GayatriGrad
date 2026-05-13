@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Career & Networking</h1>
        <div class="prose mt-3 max-w-none">{!! $intro?->content !!}</div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <h2 class="heading-font text-3xl font-bold text-primary">Job Portal & Internship Opportunities</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-2">
        @forelse($jobs as $job)
            <article class="rounded-xl border p-5">
                <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $job->employment_type) }}</p>
                <h3 class="text-xl font-semibold">{{ $job->title }}</h3>
                <p class="text-sm text-gray-600">{{ $job->company }} • {{ $job->location }}</p>
                <div class="prose mt-2 max-w-none text-sm">{!! $job->description !!}</div>
                @if($job->apply_url)
                    <a href="{{ $job->apply_url }}" target="_blank" rel="noopener" class="mt-3 inline-block rounded bg-primary px-4 py-2 text-sm text-white">Apply</a>
                @endif
            </article>
        @empty
            <p class="text-sm text-gray-600">No active job postings available.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $jobs->links() }}</div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <h2 class="heading-font text-3xl font-bold text-primary">Mentorship Program & Business Networking</h2>
    <div class="mt-4 grid gap-4 md:grid-cols-2">
        @forelse($mentorshipPrograms as $program)
            <article class="rounded-xl border p-5">
                <p class="text-xs font-semibold uppercase text-accent">{{ $program->availability }}</p>
                <h3 class="text-xl font-semibold">{{ $program->title }}</h3>
                <p class="text-sm text-gray-600">{{ $program->mentor_name }}{{ $program->mentor_designation ? ' • ' . $program->mentor_designation : '' }}</p>
                <p class="text-sm text-gray-600">{{ $program->organization }}</p>
                <div class="prose mt-2 max-w-none text-sm">{!! $program->description !!}</div>
                <p class="mt-2 text-sm">Contact: {{ $program->contact_email }}</p>
            </article>
        @empty
            <p class="text-sm text-gray-600">No mentorship listings yet.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $mentorshipPrograms->links() }}</div>
</section>
@endsection
