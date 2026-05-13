@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Registration</h1>
        <p class="mt-3 max-w-3xl text-gray-700">
            Use the links below to register as an alumnus, update your profile, or access membership details.
        </p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-10">
    @if(! $hasAnyLink)
        <div class="rounded-xl border border-amber-300 bg-amber-50 p-5 text-amber-900">
            Registration links are not configured yet. Please update them in
            <strong>Admin &gt; Site Settings &gt; Registration Links</strong>.
        </div>
    @endif

    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @foreach($links as $link)
            <article class="rounded-xl border bg-white p-5 shadow-sm">
                <h2 class="heading-font text-xl font-bold text-primary">{{ $link['title'] }}</h2>
                <p class="mt-2 text-sm text-gray-600">{{ $link['description'] }}</p>

                @if($link['url'])
                    <a
                        href="{{ $link['url'] }}"
                        target="_blank"
                        rel="noopener"
                        class="mt-4 inline-flex rounded-md bg-primary px-4 py-2 text-sm font-semibold text-white"
                    >
                        Open Link
                    </a>
                @else
                    <p class="mt-4 text-sm font-semibold text-amber-700">Link not configured.</p>
                @endif
            </article>
        @endforeach
    </div>
</section>
@endsection
