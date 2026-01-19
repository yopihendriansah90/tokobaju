<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DISTY - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased flex items-center justify-center min-h-screen bg-[#5D8D63] text-white">
    <div class="flex flex-col items-center justify-center w-full max-w-md p-6">
        <div class="mb-8 text-center">
            @php
                $logoUrl = isset($siteSettings) && $siteSettings?->hasMedia('logo') ? $siteSettings->getFirstMediaUrl('logo') : null;
            @endphp
            <div class="mx-auto h-20 w-20 rounded-full border border-white/50 bg-white/10 flex items-center justify-center overflow-hidden">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $siteSettings->site_name ?? 'Logo' }}" class="w-full h-full object-contain">
                @else
                    <span class="text-3xl font-semibold tracking-[6px]">D</span>
                @endif
            </div>
            <h1 class="font-serif uppercase tracking-wide text-3xl mt-2">{{ $siteSettings->site_name ?? 'DISTY' }}</h1>
            <p class="text-xs">{{ $siteSettings->site_title ?? "Tampil Syar'i Gaya Masa Kini" }}</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="w-full">
            @csrf

            {{-- Email/Username Input --}}
            <div class="mb-4">
                <input type="email" name="email" id="email" placeholder="Email / Username" required autofocus autocomplete="username"
                       class="w-full bg-white text-gray-800 placeholder-gray-500 py-3 px-6 rounded-full border-none focus:ring-2 focus:ring-[#5D8D63] focus:border-transparent">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
            </div>

            {{-- Password Input --}}
            <div class="mb-6">
                <input type="password" name="password" id="password" placeholder="Password" required autocomplete="current-password"
                       class="w-full bg-white text-gray-800 placeholder-gray-500 py-3 px-6 rounded-full border-none focus:ring-2 focus:ring-[#5D8D63] focus:border-transparent">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
            </div>

            {{-- Login Button --}}
            <div class="mb-6">
                <button type="submit"
                        class="w-full px-6 py-3 border-2 border-white text-white font-semibold rounded-full
                               hover:bg-white hover:text-[#5D8D63] transition duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2">
                    Login
                </button>
            </div>
        </form>

        {{-- Footer Links --}}
        <div class="flex justify-between w-full text-xs">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="underline hover:text-gray-300">
                    Daftar Akun
                </a>
            @endif
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="underline hover:text-gray-300">
                    Lupa Password
                </a>
            @endif
        </div>
    </div>
</body>
</html>
