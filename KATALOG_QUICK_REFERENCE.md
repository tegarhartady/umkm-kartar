# 🚀 KATALOG PRODUK - QUICK START GUIDE

## Akses Cepat

```
URL: http://localhost:8000/katalog
Route: GET /katalog
Controller: CatalogController@indexProducts
View: resources/views/pages/katalog.blade.php
```

---

## 6 Cara Menggunakan Filter

### 1. 🔍 Search Produk
```
URL: /katalog?search=sambal
Input: Text field untuk nama/deskripsi produk
Contoh: search=sambal akan menampilkan semua produk dengan "sambal" di nama atau deskripsi
```

### 2. 📂 Filter Kategori
```
URL: /katalog?kategori=Makanan%20Olahan
Input: Radio button dari database (DISTINCT kategori)
Contoh: kategori=Makanan Olahan akan menampilkan hanya produk di kategori itu
```

### 3. 🏪 Filter UMKM/Toko
```
URL: /katalog?umkm_id=5
Input: Dropdown select dari database (hanya UMKM dengan produk published)
Contoh: umkm_id=5 akan menampilkan hanya produk dari UMKM ID 5
```

### 4. 💰 Filter Harga
```
URL: /katalog?harga_min=10000&harga_max=50000
Input: Dua number field (min dan max)
Contoh: Akan menampilkan produk harga 10.000 - 50.000
```

### 5. ✅ Filter Ketersediaan
```
URL: /katalog?tersedia=1
Input: Checkbox
Contoh: tersedia=1 akan menampilkan hanya produk dengan stok > 0
```

### 6. 📊 Sorting/Urutkan
```
URL: /katalog?sort=harga_rendah
Input: Select dropdown
Opsi:
  - terbaru (default) = Produk terbaru
  - harga_rendah = Harga murah ke mahal
  - harga_tinggi = Harga mahal ke murah
  - terpopuler = Paling banyak views
```

---

## 📋 Combined Filter Examples

### Contoh 1: Cari "sambal" kategori "Makanan"
```
/katalog?search=sambal&kategori=Makanan%20Olahan
```

### Contoh 2: Harga 5k-30k, termurah, hanya tersedia
```
/katalog?harga_min=5000&harga_max=30000&sort=harga_rendah&tersedia=1
```

### Contoh 3: Dari toko ID 3, kategori "Bumbu", populer
```
/katalog?umkm_id=3&kategori=Bumbu%20Dapur&sort=terpopuler
```

### Contoh 4: Super advanced filter
```
/katalog?search=sambal&kategori=Makanan&umkm_id=3&harga_min=5000&harga_max=30000&tersedia=1&sort=harga_rendah&page=2
```

---

## 🔄 Filter Options Dynamic dari Database

Kategori dan UMKM secara otomatis di-load dari database:

```php
// Di CatalogController
$categories = Product::where('status', 'published')
    ->distinct()
    ->pluck('kategori')
    ->filter()
    ->sort();

$umkms = \App\Models\Umkm::whereHas('products', function ($q) {
    $q->where('status', 'published');
})->get();
```

Ini berarti:
- ✅ Kategori selalu update otomatis saat ada produk baru
- ✅ UMKM list hanya menampilkan yang punya produk published
- ✅ Tidak perlu hardcode atau manual update

---

## 📱 Responsive Breakpoints

| Device | Grid Columns | Behavior |
|--------|-------------|----------|
| Desktop (> 1200px) | 4 columns | Full sidebar + large grid |
| Tablet (768px - 1199px) | 2-3 columns | Medium sidebar + medium grid |
| Mobile (< 768px) | 1-2 columns | Compact filter + small grid |
| Very Small (< 576px) | 1 column | Single column grid |

---

## 🎨 UI Components

### Hero Section
- Gradient background (#667eea → #764ba2)
- Title "Katalog Produk"
- Subtitle

### Sidebar Filter Panel
- Search input
- Category radio buttons
- UMKM dropdown
- Price range inputs
- Availability checkbox
- Sort options
- Apply & Reset buttons
- Statistics dashboard

### Product Grid
- Grid dengan gap 2rem
- Responsive layout
- Product cards 280px minimum width
- 12 items per page (paginated)

### Product Cards
- Image dengan hover zoom effect
- Category badge
- Stock status badge (Tersedia/Habis)
- Product name (truncated)
- Description (60 chars max)
- UMKM name dengan link
- Price display
- Stock counter
- "Pesan" (Order) button
- Disabled state jika stok habis

### Statistics Box
- Total produk ditemukan
- Total UMKM
- Total kategori

---

## 🔍 Database Queries

### Main Query (Products)
```sql
SELECT * FROM products
WHERE status = 'published'
  AND (nama_produk LIKE '%search%' OR deskripsi LIKE '%search%')
  AND kategori = 'Makanan Olahan' -- if kategori filter
  AND umkm_id = 5 -- if umkm_id filter
  AND harga >= 10000 -- if harga_min
  AND harga <= 50000 -- if harga_max
  AND stok > 0 -- if tersedia filter
ORDER BY created_at DESC -- or based on sort option
LIMIT 12 OFFSET 0
```

### Categories Query
```sql
SELECT DISTINCT kategori FROM products
WHERE status = 'published' AND kategori IS NOT NULL AND kategori != ''
ORDER BY kategori ASC
```

### UMKM Query
```sql
SELECT u.* FROM umkms u
WHERE EXISTS (
  SELECT 1 FROM products p
  WHERE p.umkm_id = u.id AND p.status = 'published'
)
```

---

## 🛠️ Customization Quick Tips

### Menambah Filter Baru (misalnya: Rating)
1. **Controller**: Tambah query condition
   ```php
   if ($request->filled('rating')) {
       $query->where('rating', '>=', $request->input('rating'));
   }
   ```

2. **View**: Tambah form element
   ```blade
   <select class="form-select" name="rating">
       <option value="">Semua Rating</option>
       <option value="4">⭐⭐⭐⭐ (4+)</option>
       <option value="5">⭐⭐⭐⭐⭐ (5)</option>
   </select>
   ```

### Ubah Jumlah Items per Page
```php
// Di indexProducts() ubah dari:
$products = $query->paginate(12)->withQueryString();
// Menjadi:
$products = $query->paginate(20)->withQueryString();
```

### Tambah Sorting Option Baru (misalnya: Populer Minggu Ini)
```php
case 'populer_minggu':
    $query->where('created_at', '>=', now()->subDays(7))
          ->orderBy('views', 'desc');
    break;
```

---

## ✅ Testing Checklist

- [ ] Akses /katalog menampilkan semua produk
- [ ] Search input berfungsi
- [ ] Kategori filter bekerja
- [ ] UMKM filter bekerja
- [ ] Price range bekerja
- [ ] Tersedia checkbox bekerja
- [ ] Sorting semua opsi bekerja
- [ ] Pagination bekerja
- [ ] Reset button mengembalikan ke default
- [ ] Combined filters bekerja
- [ ] Responsive mobile OK
- [ ] Responsive tablet OK
- [ ] Responsive desktop OK
- [ ] Product cards tampil dengan benar
- [ ] Hover effects bekerja
- [ ] "Pesan" button bekerja
- [ ] Statistics update otomatis

---

## 🐛 Troubleshooting

### Filter tidak bekerja
**Solusi**: Pastikan products di database punya status = 'published'

### Kategori tidak muncul di dropdown
**Solusi**: Pastikan ada produk published dengan kategori yang tidak kosong

### UMKM tidak muncul
**Solusi**: Pastikan UMKM tersebut punya produk dengan status 'published'

### Harga filter tidak akurat
**Solusi**: Pastikan kolom harga di database tipe DECIMAL, bukan TEXT

### Pagination lambat
**Solusi**: Tambah database indexes pada columns: status, kategori, umkm_id, harga

---

## 📊 File Structure

```
/katalog (route)
  ├── CatalogController.php
  │   └── indexProducts()      // Main logic
  └── resources/views/pages/katalog.blade.php
      ├── Hero section
      ├── Filter sidebar
      │   ├── Search
      │   ├── Categories
      │   ├── UMKMs
      │   ├── Price range
      │   ├── Availability
      │   └── Buttons
      ├── Products grid
      │   └── Product cards x12
      ├── Statistics
      └── Pagination
```

---

## 🚀 Performance Notes

- ✅ Eager loading dengan `with('umkm')`
- ✅ Pagination untuk prevent memory issues
- ✅ Distinct select untuk categories
- ✅ Query string preservation untuk navigation
- ⚡ Ready untuk database indexes
- 🔄 Ready untuk query caching

---

## 📞 Quick Reference

| Function | URL Parameter | Example |
|----------|--------------|---------|
| Search | `search` | `search=sambal` |
| Category | `kategori` | `kategori=Makanan` |
| UMKM | `umkm_id` | `umkm_id=5` |
| Min Price | `harga_min` | `harga_min=10000` |
| Max Price | `harga_max` | `harga_max=50000` |
| Available | `tersedia` | `tersedia=1` |
| Sort | `sort` | `sort=harga_rendah` |
| Page | `page` | `page=2` |

---

**Status**: ✅ Production Ready
**Last Update**: April 13, 2026
**Support**: Check KATALOG_DATABASE_INTEGRATION.md for detailed docs
