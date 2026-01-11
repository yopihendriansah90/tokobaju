@php
    $cartCount = collect(session('cart', []))->sum('quantity');
@endphp

<x-client-layout>
    <div class="min-h-screen bg-[#4f8a63] bg-gradient-to-b from-[#4f8a63] to-[#3f6d51] text-white pb-24">
        <div class="max-w-4xl mx-auto px-4 pt-4 space-y-4">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="w-11 h-11 flex items-center justify-center rounded-full bg-white/20 border border-white/30 backdrop-blur">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h2 class="font-[Playfair_Display] text-lg tracking-wide">Profil Saya</h2>
                <div class="w-11 h-11"></div>
            </div>

            @if(session('status') === 'profile-updated')
                <div class="bg-emerald-50 text-emerald-700 text-sm rounded-xl px-4 py-3">
                    Profil berhasil diperbarui.
                </div>
            @endif

            @if(session('status') === 'password-updated')
                <div class="bg-emerald-50 text-emerald-700 text-sm rounded-xl px-4 py-3">
                    Kata sandi berhasil diperbarui.
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-4">
                @csrf
                @method('patch')
                <h3 class="text-sm font-semibold">Informasi Akun</h3>
                <div class="rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-600 space-y-1">
                    <div>
                        <span class="font-semibold text-gray-800">Alamat tersimpan:</span>
                        {{ $user->address ? $user->address : 'Belum ada alamat. Silakan isi di bawah.' }}
                    </div>
                    <div>
                        <span class="font-semibold text-gray-800">No. Telepon tersimpan:</span>
                        {{ $user->phone ? $user->phone : 'Belum ada nomor telepon.' }}
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        @error('name')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        @error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]">
                        <p class="text-xs text-gray-500">Format: angka, spasi, tanda +, (), atau -.</p>
                        @error('phone')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-gray-800">Alamat</label>
                        <textarea name="address" rows="3" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]">{{ old('address', $user->address) }}</textarea>
                        <p class="text-xs text-gray-500">Alamat ini akan digunakan sebagai alamat pengiriman saat checkout.</p>
                        @error('address')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-5 py-3 bg-[#4f8a63] text-white font-semibold rounded-xl shadow hover:bg-[#3f704f] transition">
                        Simpan Profil
                    </button>
                </div>
            </form>

            <form action="{{ route('password.update') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-4">
                @csrf
                @method('put')
                <h3 class="text-sm font-semibold">Ubah Kata Sandi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-sm font-semibold text-gray-800">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        @error('current_password', 'updatePassword')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Kata Sandi Baru</label>
                        <input type="password" name="password" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                        @error('password', 'updatePassword')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-gray-800">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-5 py-3 bg-[#4f8a63] text-white font-semibold rounded-xl shadow hover:bg-[#3f704f] transition">
                        Perbarui Kata Sandi
                    </button>
                </div>
            </form>

            <form action="{{ route('profile.destroy') }}" method="POST" class="bg-white rounded-2xl shadow-xl p-4 text-gray-900 space-y-4">
                @csrf
                @method('delete')
                <h3 class="text-sm font-semibold text-red-600">Hapus Akun</h3>
                <p class="text-sm text-gray-600">Masukkan kata sandi untuk menghapus akun secara permanen.</p>
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-800">Kata Sandi</label>
                    <input type="password" name="password" class="w-full rounded-xl border-gray-200 focus:ring-[#4f8a63]" required>
                    @error('password', 'userDeletion')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-5 py-3 bg-red-600 text-white font-semibold rounded-xl shadow hover:bg-red-700 transition">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>

        @include('client.partials.bottom-nav', ['cartCount' => $cartCount])
    </div>
</x-client-layout>
