<?php

namespace App\Http\Controllers\Auth;

use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UmkmAuthController extends Controller
{
    /**
     * Show UMKM Login Form
     */
    public function showLoginForm()
    {
        return view('umkm.login');
    }

    /**
     * Handle UMKM Login
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cari UMKM berdasarkan email
        $umkm = Umkm::where('email', $credentials['email'])->first();

        // Check apakah UMKM ada dan password benar
        if (!$umkm || !Hash::check($credentials['password'], $umkm->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ])->onlyInput('email');
        }

        // Check status UMKM
        if ($umkm->status !== 'disetujui') {
            return back()->withErrors([
                'email' => 'UMKM Anda belum disetujui oleh admin.',
            ])->onlyInput('email');
        }

        // Login
        Auth::guard('umkm')->login($umkm, $request->boolean('remember'));
        
        return redirect()->intended(route('umkm.dashboard'));
    }

    /**
     * Handle UMKM Logout
     */
    public function logout(Request $request)
    {
        Auth::guard('umkm')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/umkm/login')->with('success', 'Logout berhasil');
    }
}