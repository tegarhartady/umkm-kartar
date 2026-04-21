<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UmkmRegistrationController;
use App\Http\Controllers\Auth\UmkmAuthController;
use App\Models\Desa;

// Main Routes
Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/katalog', [CatalogController::class, 'indexProducts'])->name('katalog');

// Detail produk
Route::get('/beli/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    $umkm = $product->umkm;
    $relatedProducts = \App\Models\Product::where('umkm_id', $umkm->id)
        ->where('id', '!=', $id)
        ->limit(4)
        ->get();
    
    return view('beli', compact('product', 'umkm', 'relatedProducts'));
})->name('beli');

Route::get('/desa-mitra', function () {
    return view('pages.desa-mitra');
})->name('desa-mitra');

Route::get('/csr-pik2', function () {
    return view('pages.csr-pik2');
})->name('csr-pik2');

Route::get('/tentang', function () {
    return view('pages.tentang');
})->name('tentang');

// ✨ PUBLIC REGISTRATION FORM
Route::get('/daftar-umkm', function () {
    $desas = Desa::all();
    $categories = ['Hasil Laut', 'Makanan Olahan', 'Bumbu Dapur', 'Kerajinan', 'Kuliner'];
    return view('pages.daftar-umkm', compact('desas', 'categories'));
})->name('daftar-umkm');
//     return view('pages.daftar-umkm');
// })->name('daftar-umkm');

Route::post('/daftar-umkm', [UmkmController::class, 'registerFromPublic'])->name('umkm.register.store');

// Auth routes - Unified Login
Route::get('/login', function () {
    return view('auth.login-unified');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $role = $request->input('role');
    $credentials = $request->only('email', 'password');

    if ($role === 'umkm') {
        if (Auth::guard('umkm')->attempt($credentials)) {
            $request->session()->regenerate();
            $umkm = Auth::guard('umkm')->user();
            
            if ($umkm->status !== 'disetujui') {
                Auth::guard('umkm')->logout();
                return back()->withErrors(['umkm_error' => 'Akun Anda belum disetujui oleh admin.']);
            }
            
            return redirect()->intended('/umkm/dashboard');
        }
        return back()->withErrors(['umkm_error' => 'Email atau password salah.'])->onlyInput('email');
    } elseif ($role === 'admin') {
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if (!in_array($user->role, ['admin', 'superadmin'])) {
                Auth::logout();
                return back()->withErrors(['admin_error' => 'Anda bukan admin.']);
            }
            
            return redirect()->intended(route('dashboard.admin'));
        }
        return back()->withErrors(['admin_error' => 'Email atau password salah.'])->onlyInput('email');
    }

    return back()->withErrors(['error' => 'Pilih role login terlebih dahulu.']);
})->name('login.authenticate');

Route::post('/logout', function (Illuminate\Http\Request $request) {
    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Admin routes
Route::middleware(['auth', 'admin.superadmin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    
    // UMKM Management
    Route::resource('admin/umkm', UmkmController::class, ['as' => 'admin']);
    Route::get('admin/umkm-verifikasi', [UmkmController::class, 'verifikasi'])->name('admin.umkm.verifikasi');
    Route::patch('admin/umkm/{umkm}/approve', [UmkmController::class, 'approve'])->name('admin.umkm.approve');
    Route::patch('admin/umkm/{umkm}/reject', [UmkmController::class, 'reject'])->name('admin.umkm.reject');
    Route::post('admin/umkm/{umkm}/reset-password', [UmkmController::class, 'resetPassword'])->name('admin.umkm.resetPassword');
    
    // Product Management
    Route::get('admin/products', function () {
        $products = \App\Models\Product::paginate(15);
        return view('admin.products.index', compact('products'));
    })->name('admin.products.index');
    Route::get('admin/products/create', function () {
        $umkms = \App\Models\Umkm::where('status', 'disetujui')->get();
        return view('admin.products.create', compact('umkms'));
    })->name('admin.products.create');
    Route::post('admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('admin/products/{product}/edit', function ($id) {
        $product = \App\Models\Product::findOrFail($id);
        return view('admin.products.edit', compact('product'));
    })->name('admin.products.edit');
    Route::put('admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('admin/products/{product}', function ($id) {
        $product = \App\Models\Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    })->name('admin.products.show');
    Route::get('admin/products-moderasi', function () {
        return view('admin.products.moderasi');
    })->name('admin.products.moderasi');
    
    // Desa Management
    Route::resource('admin/desa', DesaController::class, ['as' => 'admin']);
    
    // Reports
    Route::get('admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('admin/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.export.pdf');
    Route::get('admin/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('admin.laporan.export.excel');
    
    // Settings
    Route::get('admin/settings/company', [SettingController::class, 'company'])->name('admin.settings.company');
    Route::post('admin/settings/company', [SettingController::class, 'updateCompany'])->name('admin.settings.company.update');
});

// SuperAdmin only routes
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
    
    // Admin Management
    Route::get('/superadmin/admins', [SuperAdminController::class, 'manageAdmins'])->name('superadmin.admins.index');
    Route::get('/superadmin/admins/create', [SuperAdminController::class, 'createAdmin'])->name('superadmin.admins.create');
    Route::post('/superadmin/admins', [SuperAdminController::class, 'storeAdmin'])->name('superadmin.admins.store');
    Route::get('/superadmin/admins/{admin}/edit', [SuperAdminController::class, 'editAdmin'])->name('superadmin.admins.edit');
    Route::put('/superadmin/admins/{admin}', [SuperAdminController::class, 'updateAdmin'])->name('superadmin.admins.update');
    Route::delete('/superadmin/admins/{admin}', [SuperAdminController::class, 'destroyAdmin'])->name('superadmin.admins.destroy');
    
    // Settings
    Route::get('/superadmin/settings', [SuperAdminController::class, 'settings'])->name('superadmin.settings');
    Route::post('/superadmin/settings', [SuperAdminController::class, 'updateSettings'])->name('superadmin.settings.update');
});

// Admin Login - Link tersembunyi (secret URL)
Route::get('/admin-login-secret-xyz', function () {
    return view('auth.admin-login');
})->name('admin.login');

Route::post('/admin-login-secret-xyz', function (Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();
        
        // Cek apakah user adalah admin/superadmin
        $user = auth()->user();
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->intended('/dashboard/admin');
        } else {
            // Bukan admin, logout
            auth()->logout();
            return back()->withErrors([
                'email' => 'Akses ditolak. Anda bukan admin.',
            ]);
        }
    }
    
    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('admin.authenticate');

// ===== UMKM LOGIN =====
Route::post('/umkm/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (\Illuminate\Support\Facades\Auth::guard('umkm')->attempt($credentials, $request->filled('remember'))) {
        $request->session()->regenerate();
        return redirect()->route('umkm.umkm.dashboard');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ])->onlyInput('email');
})->name('umkm.authenticate')->middleware('guest:umkm');

// ===== UMKM Protected Routes (Setelah Login) =====
Route::middleware('auth:umkm')->prefix('umkm')->name('umkm.')->group(function () {
    Route::post('/logout', [UmkmAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', function () {
        $umkm = Auth::guard('umkm')->user();
        $products = $umkm->products()->latest()->paginate(10);
        $totalProducts = $umkm->products()->count();
        $activeProducts = $umkm->products()->where('status', 'aktif')->count();

        return view('umkm.dashboard', compact('umkm', 'products', 'totalProducts', 'activeProducts'));
    })->name('umkm.dashboard');

    // Dashboard
    Route::get('/dashboard', function () {
        $umkm = Auth::guard('umkm')->user();
        $products = $umkm->products()->latest()->paginate(10);
        $totalProducts = $umkm->products()->count();
        $activeProducts = $umkm->products()->where('status', 'aktif')->count();

        return view('umkm.dashboard', compact('umkm', 'products', 'totalProducts', 'activeProducts'));
    })->name('umkm.dashboard');

    // Products - Index
    Route::get('/products', function () {
        $umkm = Auth::guard('umkm')->user();
        $products = $umkm->products()->latest()->paginate(10);
        return view('umkm.products.index', compact('products'));
    })->name('products.index');

    // Products - Create
    Route::get('/products/create', function () {
        return view('umkm.products.create');
    })->name('products.create');

    // Products - Store
    Route::post('/products', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $umkm = Auth::guard('umkm')->user();
        $validated['umkm_id'] = $umkm->id;
        $validated['status'] = 'aktif';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        \App\Models\Product::create($validated);

        return redirect()->route('umkm.products.index')->with('success', 'Produk berhasil ditambahkan');
    })->name('products.store');

    // Products - Edit
    Route::get('/products/{product}/edit', function ($id) {
        $product = \App\Models\Product::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        if ($product->umkm_id !== $umkm->id) {
            abort(403);
        }

        return view('umkm.products.edit', compact('product'));
    })->name('products.edit');

    // Products - Update
    Route::put('/products/{product}', function (\Illuminate\Http\Request $request, $id) {
        $product = \App\Models\Product::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        if ($product->umkm_id !== $umkm->id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('umkm.products.index')->with('success', 'Produk berhasil diperbarui');
    })->name('products.update');

    // Products - Delete
    Route::delete('/products/{product}', function ($id) {
        $product = \App\Models\Product::findOrFail($id);
        $umkm = Auth::guard('umkm')->user();
        
        if ($product->umkm_id !== $umkm->id) {
            abort(403);
        }

        if ($product->image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('umkm.products.index')->with('success', 'Produk berhasil dihapus');
    })->name('products.destroy');
});

// Checkout & Order routes
Route::get('/checkout/{product}', [CatalogController::class, 'checkout'])->name('catalog.checkout');
Route::post('/order/{product}', [CatalogController::class, 'processOrder'])->name('catalog.order');
Route::get('/payment/{order}', [CatalogController::class, 'payment'])->name('catalog.payment');
Route::post('/payment/{order}/process', [CatalogController::class, 'processPayment'])->name('catalog.payment.process');
Route::get('/order-success/{order}', [CatalogController::class, 'success'])->name('catalog.success');

// Check routes
