<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SuperAdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $admins = User::where('role', 'admin')->count();
        $umkmUsers = User::where('role', 'umkm')->count();
        $recentUsers = User::latest()->take(5)->get();
        
        return view('superadmin.dashboard', compact('totalUsers', 'admins', 'umkmUsers', 'recentUsers'));
    }

    public function manageAdmins()
    {
        $admins = User::where('role', 'admin')->paginate(10);
        return view('superadmin.admins.index', compact('admins'));
    }

    public function createAdmin()
    {
        return view('superadmin.admins.create');
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        return redirect()->route('superadmin.admins.index')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    public function editAdmin(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }
        
        return view('superadmin.admins.edit', compact('admin'));
    }

    public function updateAdmin(Request $request, User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($validated['password']) {
            $admin->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('superadmin.admins.index')->with('success', 'Data admin berhasil diupdate!');
    }

    public function destroyAdmin(User $admin)
    {
        if ($admin->role !== 'admin') {
            abort(404);
        }

        $admin->delete();
        return redirect()->route('superadmin.admins.index')->with('success', 'Admin berhasil dihapus!');
    }

    public function settings()
    {
        // For now, we'll show basic app settings
        return view('superadmin.settings');
    }

    public function updateSettings(Request $request)
    {
        // Here you would implement settings update logic
        // For now, just return with success message
        return back()->with('success', 'Pengaturan berhasil diupdate!');
    }
}
