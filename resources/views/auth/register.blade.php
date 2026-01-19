<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>DISTY - Register</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen bg-[#5D8D63] text-white">
    <div class="min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-lg">
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

            <div class="bg-white/10 backdrop-blur rounded-3xl border border-white/20 p-6 sm:p-8 shadow-2xl">
                <div class="mb-6">
                    <h2 class="font-serif text-2xl">Buat Akun Baru</h2>
                    <p class="text-sm text-white/70">Lengkapi data di bawah untuk mulai berbelanja.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="text-sm font-medium">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                               placeholder="Nama sesuai KTP"
                               class="mt-2 w-full bg-white text-gray-800 placeholder-gray-500 py-3 px-5 rounded-2xl focus:ring-2 focus:ring-white focus:outline-none">
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <label for="email" class="text-sm font-medium">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                               placeholder="emailkamu@email.com"
                               class="mt-2 w-full bg-white text-gray-800 placeholder-gray-500 py-3 px-5 rounded-2xl focus:ring-2 focus:ring-white focus:outline-none">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="password" class="text-sm font-medium">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   placeholder="Min. 8 karakter"
                                   class="mt-2 w-full bg-white text-gray-800 placeholder-gray-500 py-3 px-5 rounded-2xl focus:ring-2 focus:ring-white focus:outline-none">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
                        </div>
                        <div>
                            <label for="password_confirmation" class="text-sm font-medium">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Ulangi password"
                                   class="mt-2 w-full bg-white text-gray-800 placeholder-gray-500 py-3 px-5 rounded-2xl focus:ring-2 focus:ring-white focus:outline-none">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full mt-2 px-6 py-3 border-2 border-white text-white font-semibold rounded-2xl hover:bg-white hover:text-[#5D8D63] transition">
                        Daftar Sekarang
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-between text-xs">
                    <span class="text-white/70">Sudah punya akun?</span>
                    <a href="{{ route('login') }}" class="underline hover:text-gray-200">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
