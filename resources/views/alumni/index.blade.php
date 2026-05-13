@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Alumni Directory</h1>
        <p class="mt-2 text-gray-700">Search alumni by name, batch, department, and location.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-8">
    <form class="grid gap-3 rounded-xl border p-4 md:grid-cols-5">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, position, organization..." class="rounded-md border px-3 py-2 md:col-span-2">
        <select name="batch" class="rounded-md border px-3 py-2">
            <option value="">Batch</option>
            @foreach($filters['batches'] as $year)
                <option value="{{ $year }}" @selected((string) request('batch') === (string) $year)>{{ $year }}</option>
            @endforeach
        </select>
        <select name="department" class="rounded-md border px-3 py-2">
            <option value="">Department</option>
            @foreach($filters['departments'] as $department)
                <option value="{{ $department }}" @selected(request('department') === $department)>{{ $department }}</option>
            @endforeach
        </select>
        <select name="location" class="rounded-md border px-3 py-2">
            <option value="">Location</option>
            @foreach($filters['locations'] as $location)
                <option value="{{ $location }}" @selected(request('location') === $location)>{{ $location }}</option>
            @endforeach
        </select>
        <button class="rounded-md bg-primary px-4 py-2 text-white md:col-span-5">Apply Filters</button>
    </form>
</section>

<section class="mx-auto max-w-7xl px-4 pb-6">
    <h2 class="heading-font text-2xl font-bold text-primary">Distinguished Alumni</h2>
    <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($distinguished as $person)
            <article class="rounded-xl border p-4">
                <h3 class="font-semibold">{{ $person->name }}</h3>
                <p class="text-sm text-gray-600">{{ $person->department }} • {{ $person->graduation_year }}</p>
                <p class="mt-2 text-sm">{{ $person->current_position }}</p>
            </article>
        @empty
            <p class="text-sm text-gray-600">No distinguished alumni marked yet.</p>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 pb-14">
    <h2 class="heading-font text-2xl font-bold text-primary">Directory Results</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse($alumni as $person)
            <article class="rounded-xl border p-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $person->profile_photo_path ? asset('storage/' . $person->profile_photo_path) : asset('images/alumni-logo.jpeg') }}" class="h-12 w-12 rounded-full object-cover" alt="{{ $person->name }}">
                    <div>
                        <h3 class="font-semibold">{{ $person->name }}</h3>
                        <p class="text-xs text-gray-600">{{ $person->graduation_year }} • {{ $person->degree }}</p>
                    </div>
                </div>
                <p class="mt-3 text-sm text-gray-700">{{ $person->department }} | {{ $person->location }}</p>
                <p class="text-sm text-gray-700">{{ $person->current_position }}{{ $person->organization ? ' @ ' . $person->organization : '' }}</p>
                @if($person->achievements)
                    <p class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($person->achievements, 120) }}</p>
                @endif
            </article>
        @empty
            <p class="text-sm text-gray-600">No alumni records match your filters.</p>
        @endforelse
    </div>
    <div class="mt-6">{{ $alumni->links() }}</div>
</section>
@endsection
