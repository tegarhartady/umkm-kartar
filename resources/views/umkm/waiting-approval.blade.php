<?php
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-2xl p-8 max-w-md w-full text-center">
        <!-- Icon -->
        <div class="mb-6 flex justify-center">
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Menunggu Persetujuan</h1>

        <!-- Message -->
        <p class="text-gray-600 mb-2">Terima kasih telah mendaftar sebagai UMKM di platform kami!</p>
        <p class="text-gray-600 mb-6">Admin sedang memverifikasi data Anda. Proses ini biasanya memakan waktu 1-2 hari kerja.</p>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 text-left">
            <p class="text-sm text-gray-700">
                <span class="font-semibold text-blue-600">💡 Tips:</span><br>
                Pastikan data yang Anda daftarkan sudah benar. Jika ada kesalahan, admin akan menghubungi Anda.
            </p>
        </div>

        <!-- Status -->
        <div class="flex items-center justify-center mb-6">
            <div class="flex space-x-2">
                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse" style="animation-delay: 0.2s"></div>
                <div class="w-3 h-3 bg-yellow-500 rounded-full animate-pulse" style="animation-delay: 0.4s"></div>
            </div>
            <span class="ml-3 text-gray-600 font-medium">Sedang diproses...</span>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('umkm.login') }}" class="bg-indigo-600 text-white font-semibold py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                Kembali ke Login
            </a>
            <a href="/" class="bg-gray-200 text-gray-800 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 transition">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-xs text-gray-500 mb-2">Ada pertanyaan?</p>
            <p class="text-sm text-gray-700">
                Hubungi admin di <span class="font-semibold text-indigo-600">admin@kartarumkm.id</span>
            </p>
        </div>
    </div>
</div>