<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\Auth\UnifiedAuthController;
use App\Http\Controllers\Umkm\UmkmDashboardController;
use App\Http\Controllers\Umkm\UmkmProductController;
use App\Http\Controllers\UmkmTransactionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UnitController;

use Illuminate\Support\Facades\Artisan;

Route::get('/debug-contact', function () {
    return \App\Models\ContactMessage::count();
});

Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate', [
            '--path' => 'database/migrations/2026_05_05_000001_create_units_table.php',
            '--force' => true
        ]);
        return "Migration finished: " . Artisan::output();
    } catch (\Exception $e) {
        return "Migration failed: " . $e->getMessage();
    }
});

Route::post('payment/callback', [PaymentCallbackController::class, 'callback'])->name('payment.callback');

// Main Routes
Route::get('/debug-routes', function() {
    $routes = Route::getRoutes();
    $output = '<table border="1"><tr><th>Method</th><th>URI</th><th>Name</th><th>Action</th></tr>';
    foreach ($routes as $route) {
        $output .= '<tr>';
        $output .= '<td>' . implode('|', $route->methods()) . '</td>';
        $output .= '<td>' . $route->uri() . '</td>';
        $output .= '<td>' . $route->getName() . '</td>';
        $output .= '<td>' . $route->getActionName() . '</td>';
        $output .= '</tr>';
    }
    $output .= '</table>';
    return $output;
});

Route::get('/clear-cache', function() {
    try {
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        return "Cache cleared successfully! <br><a href='/debug-routes'>Check Routes</a> | <a href='/admin/settings/payment'>Go to Payment Settings</a>";
    } catch (\Exception $e) {
        return "Failed to clear cache: " . $e->getMessage();
    }
});

Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'BankSeeder', '--force' => true]);
        return "Migration and Seeding successful! <br><a href='/admin/master/banks'>Back to Master Bank</a>";
    } catch (\Exception $e) {
        return "Process failed: " . $e->getMessage();
    }
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/katalog', [CatalogController::class, 'indexProducts'])->name('katalog');
Route::get('/event/{id}', [PagesController::class, 'event'])->name('event');

// Cart Routes (Protected by Auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/checkout', [\App\Http\Controllers\CartController::class, 'processCheckout'])->name('cart.checkout.process');
});

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

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Detail produk
Route::get('/beli/{id}', [HomeController::class, 'showProduct'])->name('beli');

// Static Pages
Route::get('/desa-mitra', [PagesController::class, 'desaMitra'])->name('desa-mitra');
Route::get('/csr-pik2', [PagesController::class, 'csrPik2'])->name('csr-pik2');
Route::get('/tentang', [PagesController::class, 'tentang'])->name('tentang');

// ✨ PUBLIC REGISTRATION FORM
Route::get('/daftar-umkm', [PagesController::class, 'daftarUmkm'])->name('daftar-umkm');
Route::post('/daftar-umkm', [UmkmController::class, 'registerFromPublic'])->name('umkm.register.store');

// Unified Auth routes
Route::get('/login', [UnifiedAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [UnifiedAuthController::class, 'authenticate'])->name('login.authenticate');

Route::get('/register', [UnifiedAuthController::class, 'showRegister'])->name('register.customer');
Route::post('/register', [UnifiedAuthController::class, 'register'])->name('register.customer.store');

Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'admin.superadmin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

    // Settings (Moved to top to prevent shadowing)
    Route::get('admin/settings/company', [SettingController::class, 'company'])->name('admin.settings.company');
    Route::post('admin/settings/company', [SettingController::class, 'updateCompany'])->name('admin.settings.company.update');
    Route::get('admin/settings/payment', [SettingController::class, 'payment'])->name('admin.settings.payment');
    Route::post('admin/settings/payment', [SettingController::class, 'updatePayment'])->name('admin.settings.payment.update');
    Route::post('admin/settings/payment/bank', [SettingController::class, 'addAdminBank'])->name('admin.settings.payment.bank.add');
    Route::delete('admin/settings/payment/bank/{bank}', [SettingController::class, 'deleteAdminBank'])->name('admin.settings.payment.bank.delete');
    Route::get('admin/settings/delivery', [SettingController::class, 'delivery'])->name('admin.settings.delivery');
    Route::post('admin/settings/delivery', [SettingController::class, 'updateDelivery'])->name('admin.settings.delivery.update');

    // UMKM Management
    Route::resource('admin/umkm', UmkmController::class, ['as' => 'admin']);
    Route::get('admin/umkm-verifikasi', [UmkmController::class, 'verifikasi'])->name('admin.umkm.verifikasi');
    Route::patch('admin/umkm/{umkm}/approve', [UmkmController::class, 'approve'])->name('admin.umkm.approve');
    Route::patch('admin/umkm/{umkm}/reject', [UmkmController::class, 'reject'])->name('admin.umkm.reject');
    Route::post('admin/umkm/{umkm}/reset-password', [UmkmController::class, 'resetPassword'])->name('admin.umkm.resetPassword');

    // Product Management
    Route::get('admin/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::get('admin/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('admin/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('admin/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('admin/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('admin/products/{product}', [ProductController::class, 'show'])->name('admin.products.show');
    Route::get('admin/products-moderasi', [ProductController::class, 'moderasi'])->name('admin.products.moderasi');
    Route::patch('admin/products/{product}/status', [ProductController::class, 'updateStatus'])->name('admin.products.updateStatus');

    // Desa Management
    Route::resource('admin/desa', DesaController::class, ['as' => 'admin']);

    // Reports
    Route::get('admin/laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('admin/laporan/transaksi-desa/export', [LaporanController::class, 'exportTransaksiDesaExcel'])->name('admin.laporan.transaksi_desa.export');
    Route::get('admin/laporan/transaksi-desa', [LaporanController::class, 'transaksiDesa'])->name('admin.laporan.transaksi_desa');

    // Master Data
    Route::resource('admin/master/categories', CategoryController::class, ['as' => 'admin.master']);
    Route::resource('admin/master/units', UnitController::class, ['as' => 'admin.master']);
    Route::get('admin/laporan/export/pdf', [LaporanController::class, 'exportPdf'])->name('admin.laporan.export.pdf');
    Route::get('admin/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('admin.laporan.export.excel');

    // Transaction Management
    Route::get('admin/transactions/export', [TransactionController::class, 'exportExcel'])->name('admin.transactions.export');
    Route::resource('admin/transactions', TransactionController::class, ['as' => 'admin']);

    // User Management
    Route::resource('admin/users', UserController::class, ['as' => 'admin']);

    // Testimonials Management
    Route::resource('admin/testimonials', \App\Http\Controllers\Admin\TestimonialController::class, [
        'as' => 'admin'
    ]);
    Route::post('admin/testimonials/{testimonial}/toggle', [\App\Http\Controllers\Admin\TestimonialController::class, 'toggle'])->name('admin.testimonials.toggle');

    Route::resource('admin/promotions', \App\Http\Controllers\Admin\PromotionController::class, [
        'as' => 'admin'
    ]);
    Route::post('admin/promotions/{promotion}/toggle', [\App\Http\Controllers\Admin\PromotionController::class, 'toggle'])->name('admin.promotions.toggle');

    Route::resource('admin/master/banks', \App\Http\Controllers\Admin\BankController::class, [
        'as' => 'admin.master'
    ]);
    Route::post('admin/master/banks/{bank}/toggle', [\App\Http\Controllers\Admin\BankController::class, 'toggle'])->name('admin.master.banks.toggle');

    Route::get('admin/contact-messages', [\App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('admin.contact_messages.index');
    Route::get('admin/contact-messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'show'])->name('admin.contact_messages.show');
    Route::delete('admin/contact-messages/{message}', [\App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('admin.contact_messages.destroy');
});

// Public Contact Form
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

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

// Admin Login - Secret URL
Route::get('/admin-login-secret-xyz', [UnifiedAuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin-login-secret-xyz', [UnifiedAuthController::class, 'authenticateAdmin'])->name('admin.authenticate');

// ===== UMKM Protected Routes =====
Route::middleware('auth:umkm')->prefix('umkm')->name('umkm.')->group(function () {
    Route::post('/logout', [UnifiedAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [UmkmDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UmkmDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UmkmDashboardController::class, 'updateProfile'])->name('profile.update');

    // Products
    Route::get('/products', [UmkmProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [UmkmProductController::class, 'create'])->name('products.create');
    Route::post('/products', [UmkmProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [UmkmProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [UmkmProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [UmkmProductController::class, 'destroy'])->name('products.destroy');

    // Transactions
    Route::get('/transactions', [UmkmTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/pendapatan', [UmkmTransactionController::class, 'income'])->name('transactions.income');
    Route::post('/transactions/{transaction}/status', [UmkmTransactionController::class, 'updateStatus'])->name('transactions.update_status');
    Route::post('/transactions/{transaction}/upload-order-photo', [UmkmTransactionController::class, 'uploadOrderPhoto'])->name('transactions.upload_order_photo');
});

// Checkout & Order routes
Route::get('/checkout/{product}', [CatalogController::class, 'checkout'])->name('catalog.checkout');
Route::post('/order/{product}', [CatalogController::class, 'processOrder'])->name('catalog.order');
Route::get('/payment/{order}', [CatalogController::class, 'payment'])->name('catalog.payment');
Route::post('/payment/{order}/process', [CatalogController::class, 'processPayment'])->name('catalog.payment.process');
Route::get('/order-success/{order}', [CatalogController::class, 'success'])->name('catalog.success');
Route::post('/transaction/{transaction}/upload-proof', [CheckoutController::class, 'uploadProof'])->name('transaction.upload_proof');
Route::post('/transaction/{transaction}/update-status', [CheckoutController::class, 'updateStatusUser'])->name('transaction.update_status_user');
Route::post('/transaction/{transaction}/cancel', [CheckoutController::class, 'cancel'])->name('transaction.cancel');
