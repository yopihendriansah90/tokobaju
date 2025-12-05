<x-client-layout>
    <div class="min-h-screen bg-gradient-to-b from-[#4f8a63] via-[#457555] to-[#3f6d51] text-white pb-24">
        <div class="max-w-4xl mx-auto px-4 pt-10 space-y-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="w-11 h-11 flex items-center justify-center rounded-full glass-panel">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M15 19l-7-7 7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h1 class="text-xl brand-heading">Tentang Perusahaan</h1>
                <div class="w-11 h-11"></div>
            </div>

            <div class="bg-white rounded-3xl card-soft text-gray-900 p-6 space-y-4">
                <h2 class="text-lg font-semibold">Disty Mall</h2>
                <p class="text-sm text-gray-700 leading-relaxed">
                    Disty Mall adalah marketplace yang menghadirkan produk modest fashion, kebutuhan rumah tangga, makanan sehat, hingga gadget terbaru.
                    Kami berkomitmen memberi pengalaman belanja aman, nyaman, dan terkurasi untuk keluarga modern.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 rounded-2xl bg-[#f6f3eb]">
                        <h3 class="text-sm font-semibold text-[#4f8a63]">Visi</h3>
                        <p class="text-xs text-gray-700 mt-2">Menjadi destinasi belanja syar'i modern yang terpercaya di Indonesia.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#f6f3eb]">
                        <h3 class="text-sm font-semibold text-[#4f8a63]">Misi</h3>
                        <p class="text-xs text-gray-700 mt-2">Menyediakan produk berkualitas, layanan responsif, dan transaksi aman.</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-[#f6f3eb]">
                        <h3 class="text-sm font-semibold text-[#4f8a63]">Kontak</h3>
                        <p class="text-xs text-gray-700 mt-2">support@distymall.id<br>+62 812-3456-7890</p>
                    </div>
                </div>
            </div>
        </div>

        @include('client.partials.bottom-nav')
    </div>
</x-client-layout>
