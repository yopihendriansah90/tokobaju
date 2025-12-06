<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $siteSettings->site_title ?? config('app.name', 'Disty Mall') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if(isset($siteSettings) && $siteSettings?->hasMedia('favicon'))
        <link rel="icon" type="image/png" href="{{ $siteSettings->getFirstMediaUrl('favicon') }}">
    @endif
</head>
<body class="font-[Poppins] antialiased bg-[#4f8a63] text-white">
@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp
<div class="min-h-screen">
    {{-- Desktop top navigation --}}
    @include('client.partials.top-nav', ['cartCount' => $cartCount])

    <main class="pt-4 lg:pt-24">
        {{ $slot }}
    </main>
</div>
</body>
</html>
