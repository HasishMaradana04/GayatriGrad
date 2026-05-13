<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? ($siteSetting?->organization_name ?: 'Alumni Association') }}</title>
    <meta name="description" content="{{ $metaDescription ?? 'Official Alumni Association website' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700;900&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B2C2C',
                        accent: '#B45309',
                        soft: '#F8F6F4',
                        ink: '#1F2937',
                    },
                    fontFamily: {
                        heading: ['Merriweather', 'serif'],
                        body: ['Source Sans 3', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Source Sans 3', sans-serif; color: #1F2937; }
        .heading-font { font-family: 'Merriweather', serif; }
    </style>
</head>
<body class="bg-white text-ink">
<header class="border-b border-primary/10 bg-white/95 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 py-2 text-sm text-primary sm:flex sm:justify-between">
        <p>{{ $siteSetting?->organization_name ?: 'Alumni Association' }}</p>
        <p>{{ $siteSetting?->contact_email }}</p>
    </div>
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ $siteSetting?->logo_path ? asset('storage/' . $siteSetting->logo_path) : asset('images/alumni-logo.jpeg') }}" alt="Logo" class="h-12 w-12 rounded-full object-cover ring-2 ring-primary/20">
            <div>
                <p class="heading-font text-lg font-bold text-primary">{{ $siteSetting?->short_name ?: 'Alumni Association' }}</p>
                <p class="text-xs text-gray-600">{{ $siteSetting?->organization_name ?: 'University Community Network' }}</p>
            </div>
        </a>

        <nav class="hidden items-center gap-6 text-sm font-semibold lg:flex">
            <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
            <a href="{{ route('about') }}" class="hover:text-primary">About Us</a>
            <a href="{{ route('alumni.index') }}" class="hover:text-primary">Alumni Directory</a>
            <a href="{{ route('events.index') }}" class="hover:text-primary">Events</a>
            <a href="{{ route('news.index') }}" class="hover:text-primary">News & Updates</a>
            <a href="{{ route('registration.index') }}" class="hover:text-primary">Registration</a>
            <a href="{{ route('contributions.index') }}" class="hover:text-primary">Contributions</a>
            <a href="{{ route('career.index') }}" class="hover:text-primary">Career & Networking</a>
            <a href="{{ route('gallery.index') }}" class="hover:text-primary">Gallery</a>
            <a href="{{ route('contact.index') }}" class="hover:text-primary">Contact Us</a>
        </nav>

        <details class="lg:hidden">
            <summary class="cursor-pointer rounded-md border border-primary/20 px-3 py-2 text-sm">Menu</summary>
            <div class="absolute right-4 z-10 mt-2 w-64 space-y-1 rounded-lg border bg-white p-3 shadow-lg">
                <a href="{{ route('home') }}" class="block rounded px-2 py-1 hover:bg-soft">Home</a>
                <a href="{{ route('about') }}" class="block rounded px-2 py-1 hover:bg-soft">About Us</a>
                <a href="{{ route('alumni.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Alumni Directory</a>
                <a href="{{ route('events.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Events</a>
                <a href="{{ route('news.index') }}" class="block rounded px-2 py-1 hover:bg-soft">News & Updates</a>
                <a href="{{ route('registration.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Registration</a>
                <a href="{{ route('contributions.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Contributions</a>
                <a href="{{ route('career.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Career & Networking</a>
                <a href="{{ route('gallery.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Gallery</a>
                <a href="{{ route('contact.index') }}" class="block rounded px-2 py-1 hover:bg-soft">Contact Us</a>
            </div>
        </details>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer class="mt-16 bg-primary text-white">
    <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 md:grid-cols-3">
        <div>
            <h3 class="heading-font text-xl font-bold">Quick Links</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-amber-200">About Us</a></li>
                <li><a href="{{ route('events.index') }}" class="hover:text-amber-200">Events</a></li>
                <li><a href="{{ route('news.index') }}" class="hover:text-amber-200">News</a></li>
                <li><a href="{{ route('alumni.index') }}" class="hover:text-amber-200">Alumni Directory</a></li>
                <li><a href="{{ route('contact.index') }}" class="hover:text-amber-200">Contact</a></li>
            </ul>
        </div>
        <div>
            <h3 class="heading-font text-xl font-bold">Registration Portal</h3>
            <ul class="mt-4 space-y-2 text-sm">
                <li><a href="{{ route('registration.index') }}" class="hover:text-amber-200">Open Registration Page</a></li>
                <li><a target="_blank" rel="noopener" href="{{ $siteSetting?->registration_new_alumni_url ?: '#' }}" class="hover:text-amber-200">New Alumni Registration</a></li>
                <li><a target="_blank" rel="noopener" href="{{ $siteSetting?->registration_update_profile_url ?: '#' }}" class="hover:text-amber-200">Update Profile</a></li>
                <li><a target="_blank" rel="noopener" href="{{ $siteSetting?->registration_membership_details_url ?: '#' }}" class="hover:text-amber-200">Membership Details</a></li>
                <li><a target="_blank" rel="noopener" href="{{ $siteSetting?->registration_login_url ?: '#' }}" class="hover:text-amber-200">Login</a></li>
            </ul>
        </div>
        <div>
            <h3 class="heading-font text-xl font-bold">Contact</h3>
            <p class="mt-4 text-sm">{{ $siteSetting?->contact_address }}</p>
            <p class="text-sm">{{ $siteSetting?->contact_phone_primary }}</p>
            <p class="text-sm">{{ $siteSetting?->contact_email }}</p>
        </div>
    </div>
    <div class="border-t border-white/20 py-3 text-center text-xs">
        {{ $siteSetting?->footer_text ?: 'All rights reserved.' }}
    </div>
</footer>
</body>
</html>
