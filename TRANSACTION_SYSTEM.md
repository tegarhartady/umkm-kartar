# 📋 Transaction System Documentation

## Overview
Sistem transaksi telah berhasil diimplementasikan untuk mengelola proses pembelian produk dari customer hingga pembayaran dan pengiriman.

## 🗄️ Database Structure

### Transactions Table
Tabel `transactions` menyimpan semua data transaksi dengan struktur:

```sql
CREATE TABLE transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    transaction_code VARCHAR(255) UNIQUE,
    user_id BIGINT FOREIGN KEY,
    product_id BIGINT FOREIGN KEY,
    quantity INTEGER,
    price DECIMAL(15,2),
    total_price DECIMAL(15,2),
    status ENUM('pending', 'completed', 'cancelled', 'failed'),
    payment_method ENUM('transfer', 'ewallet', 'cod'),
    buyer_name VARCHAR(255),
    buyer_phone VARCHAR(20),
    buyer_address TEXT,
    buyer_city VARCHAR(100),
    buyer_postal_code VARCHAR(10),
    notes TEXT,
    paid_at TIMESTAMP,
    completed_at TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
)
```

## 📁 File Structure

### Models
- **`app/Models/Transaction.php`**
  - Model Eloquent untuk tabel transactions
  - Relationships: belongsTo(User), belongsTo(Product)
  - Casts: decimal fields, datetime timestamps

### Controllers
- **`app/Http/Controllers/Admin/TransactionController.php`**
  - Resource controller untuk admin dashboard
  - Methods:
    - `index()` - List semua transaksi dengan pagination
    - `create()` - Show form create transaksi
    - `store()` - Store transaksi baru
    - `show()` - Display detail transaksi
    - `edit()` - Show form edit transaksi
    - `update()` - Update transaksi
    - `destroy()` - Delete transaksi

- **`app/Http/Controllers/CheckoutController.php`**
  - Controller untuk proses checkout customer
  - Methods:
    - `show($productId)` - Show checkout form
    - `store(Request $request)` - Store transaksi dari customer
    - `success($transactionId)` - Show success page

### Views

#### Admin Panel (`resources/views/admin/transactions/`)
1. **`index.blade.php`** - Daftar transaksi dengan tabel responsif
   - Menampilkan: Kode transaksi, pembeli, produk, qty, total, status, metode bayar, tanggal
   - Fitur: Pagination, action buttons (view, edit, delete)
   - Responsive design untuk mobile

2. **`create.blade.php`** - Form tambah transaksi manual
   - Input: User/Pembeli, Produk (auto-fill harga), Qty, Harga Satuan
   - Metode pembayaran
   - Data pembeli lengkap
   - Catatan opsional

3. **`show.blade.php`** - Detail transaksi
   - Informasi transaksi (kode, status, metode bayar, tanggal)
   - Informasi produk (nama, harga, qty, total)
   - Informasi pembeli lengkap
   - Action buttons (edit, delete)

4. **`edit.blade.php`** - Form edit transaksi
   - Edit status transaksi
   - Edit metode pembayaran
   - Edit informasi pembeli
   - Timestamp tracking untuk paid_at dan completed_at

#### Customer Checkout (`resources/views/checkout/`)
1. **`form.blade.php`** - Checkout form untuk customer
   - Menampilkan info produk
   - Input jumlah pembelian dengan validasi stok
   - Pilihan metode pembayaran
   - Form data pembeli (nama, telepon, alamat, kota, kode pos)
   - Ringkasan pesanan di sidebar
   - Real-time total calculation

2. **`success.blade.php`** - Halaman sukses checkout
   - Konfirmasi pesanan berhasil dibuat
   - Menampilkan kode transaksi
   - Detail pesanan
   - Informasi pengiriman
   - Langkah-langkah pembayaran sesuai metode
   - Informasi kontak support

## 🔗 Routes

```php
// Admin Routes (Protected by auth & admin)
Route::resource('admin/transactions', TransactionController::class, ['as' => 'admin']);
// Routes generated:
// GET    /admin/transactions              -> index
// GET    /admin/transactions/create       -> create
// POST   /admin/transactions              -> store
// GET    /admin/transactions/{id}         -> show
// GET    /admin/transactions/{id}/edit    -> edit
// PUT    /admin/transactions/{id}         -> update
// DELETE /admin/transactions/{id}         -> destroy

// Public Checkout Routes
Route::get('/checkout/{productId}', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{transactionId}', [CheckoutController::class, 'success'])->name('checkout.success');
```

## 💻 How to Use

### For Customers (Checkout Flow)
1. Klik "Beli Sekarang" pada halaman detail produk (`/beli/{id}`)
2. Redirect ke form checkout (`/checkout/{productId}`)
3. Isi form:
   - Tentukan jumlah pembelian
   - Pilih metode pembayaran
   - Isi data pembeli (nama, telepon, alamat, kota, kode pos)
   - (Opsional) Tambahkan catatan
4. Klik "Lanjutkan Pembayaran"
5. Sistem akan membuat transaksi dengan:
   - Transaction code unik: `TRX-YYYYMMDDHHmmss-XXXX`
   - Status: `pending`
   - Total price otomatis terhitung
6. Redirect ke halaman sukses (`/checkout/success/{transactionId}`)
   - Menampilkan kode transaksi
   - Instruksi pembayaran sesuai metode
   - Contact untuk pertanyaan

### For Admin (Transaction Management)
1. **View List**: Akses `/admin/transactions`
   - Lihat semua transaksi dengan pagination
   - Filter berdasarkan status, metode bayar
   - Aksi: view, edit, delete

2. **Create Manually**: Klik "Tambah Transaksi"
   - Pilih user/pembeli
   - Pilih produk (harga auto-fill)
   - Tentukan qty
   - Isi data pembeli
   - Simpan

3. **View Detail**: Klik icon view
   - Lihat semua informasi transaksi lengkap
   - Edit atau delete transaksi

4. **Update Status**: 
   - Klik edit
   - Update status: pending → completed/cancelled/failed
   - Edit metode pembayaran
   - Edit data pembeli
   - Sistem auto-update timestamps (paid_at, completed_at)

## 🔐 Validation Rules

### Create/Store
```php
'product_id' => 'required|exists:products,id',
'quantity' => 'required|integer|min:1',
'payment_method' => 'required|in:transfer,ewallet,cod',
'buyer_name' => 'required|string|max:255',
'buyer_phone' => 'required|string|max:20',
'buyer_address' => 'required|string',
'buyer_city' => 'required|string|max:100',
'buyer_postal_code' => 'nullable|string|max:10',
'notes' => 'nullable|string',
```

### Update
```php
'status' => 'required|in:pending,completed,cancelled,failed',
'payment_method' => 'nullable|in:transfer,ewallet,cod',
'buyer_name' => 'required|string|max:255',
'buyer_phone' => 'required|string|max:20',
'buyer_address' => 'required|string',
'buyer_city' => 'required|string|max:100',
'buyer_postal_code' => 'nullable|string|max:10',
'notes' => 'nullable|string',
```

## 📊 Key Features

### Automatic Features
- ✅ Transaction code generation: `TRX-{timestamp}-{random}`
- ✅ Auto-calculate total_price = price × quantity
- ✅ Stock validation saat checkout
- ✅ Auto-timestamp paid_at saat status diubah
- ✅ Auto-timestamp completed_at saat selesai
- ✅ Decimal precision untuk financial data

### Status Workflow
- **pending** → Menunggu pembayaran
- **completed** → Transaksi selesai/berhasil
- **cancelled** → Dibatalkan
- **failed** → Pembayaran gagal

### Payment Methods
- **transfer** → Transfer Bank
- **ewallet** → E-Wallet (GCash, PayMaya, etc)
- **cod** → Bayar di Tempat

## 📱 Responsive Design
Semua views sudah responsive untuk:
- Desktop (1200px+)
- Tablet (768px - 1199px)
- Mobile (< 768px)

## 🎨 UI Components
- Bootstrap 5 cards dan components
- Bootstrap Icons untuk visual icons
- Form validation dengan feedback
- Alert messages untuk success/error
- Sticky sidebar untuk summary (checkout form)
- Pagination untuk transaction list

## 🔄 Integration Points

### With Product Catalog
- Customer bisa checkout dari halaman detail produk
- Auto-populate harga produk
- Stock validation sebelum checkout

### With User System
- Optional user_id jika customer logged in
- Fallback untuk guest checkout (user_id nullable)

### With Dashboard (Laporan)
- Transaction stats dapat ditampilkan di laporan
- Total revenue calculation

## 📋 Next Steps (Optional Enhancements)

1. **Email Notifications**
   - Send confirmation email to customer after checkout
   - Send payment instructions based on payment method

2. **SMS Integration**
   - Send transaction code via SMS
   - Send payment reminders

3. **Payment Gateway Integration**
   - Stripe untuk credit card
   - PayPal integration
   - Midtrans untuk local payment methods

4. **Invoice Generation**
   - PDF invoice creation
   - Invoice download feature

5. **Transaction History**
   - Customer dashboard to view their transactions
   - Download invoice/receipt

6. **Admin Reports**
   - Transaction analytics
   - Revenue reports by date range
   - Most sold products
   - Payment method analytics

7. **Inventory Management**
   - Auto-reduce stock saat transaction created
   - Low stock alerts

## ✅ Testing

### Test Checkout Flow
1. Go to `/katalog`
2. Click on a product
3. Click "Beli Sekarang"
4. Fill checkout form with test data
5. Submit form
6. Verify transaction created in DB
7. Check success page displays correct info

### Test Admin Panel
1. Go to `/admin/transactions`
2. View list (should be empty initially or show previous test transactions)
3. Create new transaction manually
4. Edit transaction status
5. View transaction detail
6. Delete transaction

### Test Validation
1. Try checkout with:
   - Empty required fields
   - Quantity > available stock
   - Invalid phone number
2. Verify validation messages appear

## 📝 Database Migration Command
```bash
php artisan migrate --path=database/migrations/2026_04_26_082909_create_transactions_table.php
```

## 🚀 Deployment Notes
- Ensure `transactions` table created before going live
- Configure mail service for email notifications (future)
- Set proper payment gateway credentials (future)
- Test checkout flow in staging environment

---

**Created:** April 26, 2026
**Last Updated:** April 26, 2026
**Status:** ✅ Complete & Ready to Test
