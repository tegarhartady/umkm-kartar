@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8">
        <h1 class="text-3xl font-bold text-center mb-8">Daftar UMKM</h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('umkm.store') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nama UMKM</label>
                <input type="text" name="nama_umkm" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                @error('nama_umkm')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Nomor Telepon</label>
                <input type="tel" name="nomor_telepon" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-2">Alamat</label>
                <textarea name="alamat" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="3" required></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition">
                Daftar
            </button>
        </form>

        <p class="text-center mt-4">Sudah punya akun? <a href="{{ route('auth.login') }}" class="text-blue-600 hover:underline font-bold">Masuk di sini</a></p>
    </div>
</div>
@endsection
