# 🚀 KATALOG PRODUK - QUICK START GUIDE

## ⚡ Untuk Developer

### Testing Katalog
```bash
# Start Laravel server
php artisan serve

# Visit catalog page
http://localhost:8000/katalog
```

### How Filters Work

**Search (Real-time)**
```
User types "ikan" → JavaScript builds query
→ URL: /katalog?search=ikan
→ Controller queries: WHERE nama_produk LIKE '%ikan%' OR deskripsi LIKE '%ikan%'
```

**Category Filter**
```
User clicks "Hasil Laut" → JavaScript sends filter
→ URL: /katalog?kategori=Hasil%20Laut
→ Controller queries: WHERE kategori = 'Hasil Laut'
```

**Desa Filter**
```
User clicks "Teluknaga" → JavaScript sends filter
→ URL: /katalog?desa=Teluknaga
→ Controller queries: UMKM.desa = 'Teluknaga' (via relationship)
```

**Combined Filters**
```
URL: /katalog?search=ikan&kategori=Hasil%20Laut&desa=Teluknaga&sort=harga_rendah
```

---

## 📋 Key Files

### Controller
```
app/Http/Controllers/CatalogController.php
  └─ indexProducts() method handles all filtering
```

### View
```
resources/views/pages/katalog.blade.php
  └─ Displays products from database
  └─ Dynamic filter pills from database
  └─ JavaScript handles filter events
```

### Route
```
Route::get('/katalog', [CatalogController::class, 'indexProducts'])
  ->name('katalog');
```

---

## 🔧 Adding New Filters

### Step 1: Add filter logic in CatalogController
```php
// In indexProducts() method
if ($request->filled('your_filter')) {
    $query->where('your_column', $request->input('your_filter'));
}
```

### Step 2: Add UI in katalog.blade.php
```blade
<div class="filter-section">
    <h6 class="filter-label">Your Filter</h6>
    <div class="filter-pills">
        <button class="filter-pill active" data-filter="your_filter" data-value="">
            Semua
        </button>
        @foreach($your_options as $option)
        <button class="filter-pill" data-filter="your_filter" data-value="{{ $option }}">
            {{ $option }}
        </button>
        @endforeach
    </div>
</div>
```

### Step 3: Update JavaScript (automatic)
The JavaScript already handles any filter with `data-filter` attribute!

---

## 📊 Database Structure

### Products Table
```sql
id          INT PRIMARY KEY
nama_produk VARCHAR
kategori    VARCHAR
harga       DECIMAL
stok        INT
status      VARCHAR (published/draft)
umkm_id     INT FK
created_at  TIMESTAMP
updated_at  TIMESTAMP
```

### UMKM Table
```sql
id          INT PRIMARY KEY
nama_toko   VARCHAR
desa        VARCHAR (foreign key to desas table)
```

### Desa Table
```sql
id          INT PRIMARY KEY
nama_desa   VARCHAR
```

---

## 🎯 Common Queries

### Get all published products
```php
Product::where('status', 'published')->get();
```

### Get products by category
```php
Product::where('kategori', 'Hasil Laut')->get();
```

### Get products from specific desa
```php
Product::whereHas('umkm', function ($q) {
    $q->where('desa', 'Teluknaga');
})->get();
```

### Get products in price range
```php
Product::whereBetween('harga', [50000, 500000])->get();
```

### Get available products only
```php
Product::where('stok', '>', 0)->get();
```

---

## 🧪 Testing

### Test Search
```
http://localhost:8000/katalog?search=ikan
```

### Test Category Filter
```
http://localhost:8000/katalog?kategori=Hasil%20Laut
```

### Test Desa Filter
```
http://localhost:8000/katalog?desa=Teluknaga
```

### Test Price Range
```
http://localhost:8000/katalog?harga_min=50000&harga_max=200000
```

### Test Sorting
```
http://localhost:8000/katalog?sort=harga_rendah
http://localhost:8000/katalog?sort=harga_tinggi
http://localhost:8000/katalog?sort=terpopuler
```

### Test Combined
```
http://localhost:8000/katalog?search=ikan&kategori=Hasil%20Laut&desa=Teluknaga&sort=harga_rendah&page=2
```

---

## ⚙️ Configuration

### Items Per Page
```php
// In CatalogController.php
$products = $query->paginate(12); // Change 12 to any number
```

### Sort Options
```php
// Can modify in controller switch statement
case 'custom_sort':
    $query->orderBy('column', 'direction');
    break;
```

### Search Fields
```php
// In controller search section
$query->where(function ($q) use ($search) {
    $q->where('nama_produk', 'like', "%{$search}%")
      ->orWhere('deskripsi', 'like', "%{$search}%")
      ->orWhere('kategori', 'like', "%{$search}%"); // Add more fields
});
```

---

## 🎨 Design Notes

- Original design completely preserved
- No CSS changes from original
- No layout changes from original
- Only functionality was enhanced

---

## 📈 Performance Tips

1. **Database Indexes**
   - Index `status` column in products table
   - Index `kategori` column
   - Index `kategori` in umkm table for relationships

2. **Query Optimization**
   - Already using eager loading with `with('umkm')`
   - Already using `distinct()` for filter options

3. **Caching**
   - Consider caching categories list
   - Consider caching desas list

---

## 🐛 Troubleshooting

### Filters not working?
1. Check if products have `status = 'published'`
2. Check if query parameters are being passed
3. Check browser console for JavaScript errors

### Dynamic filters not showing?
1. Verify categories/desas exist in database
2. Verify they have products linked to them
3. Check controller returns data to view

### Pagination not working?
1. Check if `paginate(12)` is used in controller
2. Verify pagination view is included
3. Check query strings are preserved

---

## 📝 SQL Queries Reference

### Get all categories
```sql
SELECT DISTINCT kategori FROM products WHERE status = 'published';
```

### Get all desas with products
```sql
SELECT d.* FROM desas d
INNER JOIN umkms u ON d.id = u.desa_id
INNER JOIN products p ON u.id = p.umkm_id
WHERE p.status = 'published'
GROUP BY d.id;
```

### Get filtered products
```sql
SELECT p.* FROM products p
INNER JOIN umkms u ON p.umkm_id = u.id
WHERE p.status = 'published'
AND p.kategori = 'Hasil Laut'
AND u.desa = 'Teluknaga'
AND p.harga BETWEEN 50000 AND 500000
ORDER BY p.created_at DESC
LIMIT 12;
```

---

**Last Updated**: Session Complete  
**Status**: Production Ready ✅
