@extends('layouts.app')

@section('content')
<section class="bg-soft py-12">
    <div class="mx-auto max-w-7xl px-4">
        <h1 class="heading-font text-4xl font-black text-primary">Contact Us</h1>
        <div class="prose mt-3 max-w-none">{!! $intro?->content !!}</div>
    </div>
</section>

<section class="mx-auto grid max-w-7xl gap-8 px-4 py-10 lg:grid-cols-2">
    <div class="rounded-xl border p-6">
        <h2 class="heading-font text-2xl font-bold text-primary">Contact Details</h2>
        <p class="mt-4 text-sm">{{ $siteSetting?->contact_address }}</p>
        <p class="text-sm">{{ $siteSetting?->contact_phone_primary }}</p>
        <p class="text-sm">{{ $siteSetting?->contact_phone_secondary }}</p>
        <p class="text-sm">{{ $siteSetting?->contact_email }}</p>

        <h3 class="mt-6 text-lg font-semibold">Social Media</h3>
        <ul class="mt-2 space-y-2 text-sm">
            @if($siteSetting?->facebook_url)<li><a href="{{ $siteSetting->facebook_url }}" target="_blank" rel="noopener">Facebook</a></li>@endif
            @if($siteSetting?->linkedin_url)<li><a href="{{ $siteSetting->linkedin_url }}" target="_blank" rel="noopener">LinkedIn</a></li>@endif
            @if($siteSetting?->instagram_url)<li><a href="{{ $siteSetting->instagram_url }}" target="_blank" rel="noopener">Instagram</a></li>@endif
            @if($siteSetting?->x_url)<li><a href="{{ $siteSetting->x_url }}" target="_blank" rel="noopener">X</a></li>@endif
            @if($siteSetting?->youtube_url)<li><a href="{{ $siteSetting->youtube_url }}" target="_blank" rel="noopener">YouTube</a></li>@endif
        </ul>
    </div>

    <div class="rounded-xl border p-6">
        <h2 class="heading-font text-2xl font-bold text-primary">Contact Form</h2>
        @if(session('status'))
            <p class="mt-3 rounded-md bg-green-100 px-3 py-2 text-sm text-green-700">{{ session('status') }}</p>
        @endif
        <form method="POST" action="{{ route('contact.store') }}" class="mt-4 space-y-3">
            @csrf
            <input name="name" value="{{ old('name') }}" placeholder="Your Name" class="w-full rounded-md border px-3 py-2" required>
            <input name="email" type="email" value="{{ old('email') }}" placeholder="Email Address" class="w-full rounded-md border px-3 py-2" required>
            <input name="phone" value="{{ old('phone') }}" placeholder="Phone Number" class="w-full rounded-md border px-3 py-2">
            <input name="subject" value="{{ old('subject') }}" placeholder="Subject" class="w-full rounded-md border px-3 py-2" required>
            <textarea name="message" rows="5" placeholder="Message" class="w-full rounded-md border px-3 py-2" required>{{ old('message') }}</textarea>
            <button class="rounded-md bg-primary px-5 py-2 text-white">Send Message</button>
        </form>
        @if($errors->any())
            <ul class="mt-3 text-sm text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
@endsection
