<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'email' => 'required|email|unique:umkms',
            'phone' => 'required|string|max:20',
            'desa' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kategori' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'deskripsi' => 'nullable|string',
        ]);

        $umkm = Umkm::create([
            'nama_toko' => $validated['nama_toko'],
            'pemilik' => $validated['pemilik'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'desa' => $validated['desa'],
            'alamat' => $validated['alamat'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => 'pending',
        ]);

        Auth::guard('umkm')->login($umkm);

        return redirect()->route('umkm.dashboard')->with('success', 'Registrasi berhasil!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('umkm')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('umkm.dashboard')->with('success', 'Login berhasil!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('umkm')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout berhasil!');
    }
}
