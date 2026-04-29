<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DesaController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UmkmRegistrationController;
use App\Http\Controllers\Auth\UmkmAuthController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Models\Desa;

Route::post('payment/callback', [PaymentCallbackController::class, 'callback'])->name('payment.callback');

// Main Routes
Route::get('/', function () {
    $recommendedProducts = \App\Models\Product::where('status', 'aktif')
        ->limit(4)
        ->get();
    return view('index', compact('recommendedProducts'));
})->name('home');

Route::get('/katalog', [CatalogController::class, 'indexProducts'])->name('katalog');

// Checkout routes (Protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout/{productId}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{transactionId}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
});

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
    $credentials = $request->only('email', 'password');

    // Coba login sebagai UMKM
    if (Auth::guard('umkm')->attempt($credentials)) {
        $request->session()->regenerate();
        $umkm = Auth::guard('umkm')->user();
        
        if ($umkm->status !== 'disetujui') {
            Auth::guard('umkm')->logout();
            return back()->withErrors(['email' => 'Akun UMKM Anda belum disetujui oleh admin.'])->onlyInput('email');
        }
        
        return redirect()->intended('/umkm/dashboard');
    }

    // Coba login sebagai User (Admin / Superadmin / Pelanggan)
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->intended(route('dashboard.admin'));
        }
        
        // Pelanggan biasa
        return redirect()->intended('/');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
})->name('login.authenticate');

// Customer Registration
Route::get('/register', function () {
    return view('auth.register-customer');
})->name('register.customer');

Route::post('/register', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => 'user', // Default customer role
    ]);

    Auth::login($user);

    return redirect('/')->with('success', 'Pendaftaran berhasil! Selamat datang.');
})->name('register.customer.store');

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
    
    // Transaction Management
    Route::resource('admin/transactions', TransactionController::class, ['as' => 'admin']);
    
    // Settings
    Route::get('admin/settings/company', [SettingController::class, 'company'])->name('admin.settings.company');
    Route::post('admin/settings/company', [SettingController::class, 'updateCompany'])->name('admin.settings.company.update');
    Route::get('admin/settings/payment', [SettingController::class, 'payment'])->name('admin.settings.payment');
    Route::post('admin/settings/payment', [SettingController::class, 'updatePayment'])->name('admin.settings.payment.update');
    Route::get('admin/settings/delivery', [SettingController::class, 'delivery'])->name('admin.settings.delivery');
    Route::post('admin/settings/delivery', [SettingController::class, 'updateDelivery'])->name('admin.settings.delivery.update');
    
    // User Management
    Route::resource('admin/users', UserController::class, ['as' => 'admin']);
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
        return redirect()->route('umkm.dashboard');
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
        
        // Fetch transactions for this UMKM
        $umkmProductIds = $umkm->products()->pluck('id');
        $recentTransactions = \App\Models\Transaction::whereIn('product_id', $umkmProductIds)
            ->with('product')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();
            
        $totalTransaksi = \App\Models\Transaction::whereIn('product_id', $umkmProductIds)->count();
        $totalRevenue = \App\Models\Transaction::whereIn('product_id', $umkmProductIds)
            ->where('status', 'completed')
            ->sum('total_price');

        return view('umkm.dashboard', compact(
            'umkm', 
            'products', 
            'totalProducts', 
            'activeProducts', 
            'recentTransactions', 
            'totalTransaksi', 
            'totalRevenue'
        ));
    })->name('dashboard');


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
    
    // Transactions
    Route::get('/transactions', [\App\Http\Controllers\UmkmTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/pendapatan', [\App\Http\Controllers\UmkmTransactionController::class, 'income'])->name('transactions.income');
    Route::post('/transactions/{transaction}/status', [\App\Http\Controllers\UmkmTransactionController::class, 'updateStatus'])->name('transactions.update_status');

    // Products - Store
    Route::post('/products', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
            'satuan' => 'required|string',
            'metode_pemesanan' => 'required|in:siap_jadi,po,keduanya',
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
            'metode_pemesanan' => 'required|in:siap_jadi,po,keduanya',
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
Route::post('/transaction/{transaction}/upload-proof', [App\Http\Controllers\CheckoutController::class, 'uploadProof'])->name('transaction.upload_proof');
Route::post('/transaction/{transaction}/update-status', [App\Http\Controllers\CheckoutController::class, 'updateStatusUser'])->name('transaction.update_status_user');

// Check routes
