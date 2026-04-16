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

// Main Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/katalog', [CatalogController::class, 'indexProducts'])->name('katalog');

// Catalog routes (E-commerce)
Route::get('/beli/{product}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/checkout/{product}', [CatalogController::class, 'checkout'])->name('catalog.checkout');
Route::post('/order/{product}', [CatalogController::class, 'processOrder'])->name('catalog.order');
Route::get('/payment/{order}', [CatalogController::class, 'payment'])->name('catalog.payment');
Route::post('/payment/{order}/process', [CatalogController::class, 'processPayment'])->name('catalog.payment.process');
Route::get('/order-success/{order}', [CatalogController::class, 'success'])->name('catalog.success');

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
    return view('pages.daftar-umkm');
})->name('daftar-umkm');

Route::post('/daftar-umkm', [UmkmController::class, 'registerFromPublic'])->name('umkm.register.store');

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    
    if (auth()->attempt($credentials)) {
        $request->session()->regenerate();
        
        // Redirect based on role
        $user = auth()->user();
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return redirect()->intended('/dashboard/admin');
        } else {
            return redirect()->intended('/dashboard/umkm');
        }
    }
    
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
})->name('login.post');

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
    
    // Product Management - GANTI KE Admin ProductController
    Route::get('admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
    Route::get('admin/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
    Route::get('admin/products-moderasi', [\App\Http\Controllers\Admin\ProductController::class, 'moderasi'])->name('admin.products.moderasi');
    Route::patch('admin/products/{product}/approve', [\App\Http\Controllers\Admin\ProductController::class, 'approve'])->name('admin.products.approve');
    Route::delete('admin/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
    
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

// ===== UMKM Auth Routes (Publik) =====
Route::middleware('guest:umkm')->prefix('umkm')->name('umkm.')->group(function () {
    Route::get('/login', [UmkmAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UmkmAuthController::class, 'authenticate'])->name('authenticate');
});

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
    })->name('dashboard');

    // Products
    Route::resource('products', \App\Http\Controllers\ProductController::class);
});

// Check routes
