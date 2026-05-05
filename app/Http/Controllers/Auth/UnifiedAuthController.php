<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UnifiedAuthController extends Controller
{
    /**
     * Show unified login form
     */
    public function showLogin()
    {
        return view('auth.login-unified');
    }

    /**
     * Handle unified login for UMKM and Users
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Try login as UMKM
        if (Auth::guard('umkm')->attempt($credentials)) {
            $request->session()->regenerate();
            $umkm = Auth::guard('umkm')->user();
            
            if ($umkm->status !== 'disetujui') {
                Auth::guard('umkm')->logout();
                return back()->withErrors(['email' => 'Akun UMKM Anda belum disetujui oleh admin.'])->onlyInput('email');
            }
            
            return redirect()->intended('/umkm/dashboard');
        }

        // Try login as User (Admin / Superadmin / Pelanggan)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if (in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->intended(route('dashboard.admin'));
            }
            
            // Ordinary customer
            return redirect()->intended('/');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    /**
     * Show customer registration form
     */
    public function showRegister()
    {
        return view('auth.register-customer');
    }

    /**
     * Handle customer registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Default customer role
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Pendaftaran berhasil! Selamat datang.');
    }

    /**
     * Handle unified logout
     */
    public function logout(Request $request)
    {
        if (Auth::guard('umkm')->check()) {
            Auth::guard('umkm')->logout();
        } else {
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show admin secret login form
     */
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle admin secret login
     */
    public function authenticateAdmin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if (in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->intended('/dashboard/admin');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akses ditolak. Anda bukan admin.',
                ]);
            }
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }
}
