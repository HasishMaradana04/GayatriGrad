@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Contributions</h1>
        <div class="prose mt-3 max-w-none">{!! $intro?->content !!}</div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    <h2 class="heading-font text-3xl font-bold text-primary">Donate / Support & Endowment Funds</h2>
    <div class="mt-5 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @forelse($campaigns as $campaign)
            <article class="rounded-xl border p-5">
                <p class="text-xs font-semibold uppercase text-accent">{{ str_replace('_', ' ', $campaign->campaign_type) }}</p>
                <h3 class="mt-1 text-xl font-semibold">{{ $campaign->title }}</h3>
                <p class="mt-2 text-sm text-gray-700">{{ $campaign->summary }}</p>
                <p class="mt-2 text-sm">Target: INR {{ number_format((float) $campaign->target_amount, 2) }}</p>
                <p class="text-sm">Raised: INR {{ number_format((float) $campaign->raised_amount, 2) }}</p>
                @if($campaign->donation_url)
                    <a href="{{ $campaign->donation_url }}" target="_blank" rel="noopener" class="mt-3 inline-block rounded bg-primary px-4 py-2 text-sm text-white">Contribute</a>
                @endif
            </article>
        @empty
            <p class="text-sm text-gray-600">No donation campaigns published yet.</p>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <h2 class="heading-font text-3xl font-bold text-primary">Scholarships</h2>
    <div class="mt-5 grid gap-4 md:grid-cols-2">
        @forelse($scholarships as $scholarship)
            <article class="rounded-xl border p-5">
                <h3 class="text-xl font-semibold">{{ $scholarship->title }}</h3>
                <p class="mt-2 text-sm text-gray-700">{{ $scholarship->summary }}</p>
                @if($scholarship->deadline)
                    <p class="mt-2 text-sm text-gray-600">Deadline: {{ $scholarship->deadline->format('d M Y') }}</p>
                @endif
                @if($scholarship->application_url)
                    <a href="{{ $scholarship->application_url }}" target="_blank" rel="noopener" class="mt-3 inline-block text-sm font-semibold text-primary">Apply Now</a>
                @endif
            </article>
        @empty
            <p class="text-sm text-gray-600">No scholarships available currently.</p>
        @endforelse
    </div>
</section>
@endsection
