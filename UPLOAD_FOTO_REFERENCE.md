## 📸 FITUR UPLOAD FOTO - QUICK REFERENCE

### 🎯 Fitur yang Ditambahkan
Upload Foto KTP Pemilik dan Foto Tempat Usaha pada halaman Edit UMKM

### 📝 File yang Diubah

1. **Migration** - `database/migrations/2026_04_24_000001_add_foto_produk_utama_to_umkms.php`
   - Menambah kolom `foto_tempat` ke tabel `umkms`

2. **View** - `resources/views/admin/umkm/edit.blade.php`
   - Tambah section "Upload Foto" dengan 2 input field
   - Add `enctype="multipart/form-data"` di form
   - Preview foto yang sudah diupload

3. **Controller** - `app/Http/Controllers/UmkmController.php`
   - Update method `update()` untuk handle file upload
   - Validation rules untuk kedua foto
   - File deletion & storage logic

4. **Model** - `app/Models/Umkm.php`
   - Pastikan `foto_tempat` ada di `$fillable` array

### 🚀 Cara Menggunakan

1. **Jalankan Migration** (jika belum):
   ```bash
   php artisan migrate
   ```

2. **Edit UMKM** di Admin Panel:
   - Buka halaman Edit UMKM (`/admin/umkm/{id}/edit`)
   - Scroll ke section "Unggah Foto"
   - Upload Foto KTP Pemilik (Format: JPG/PNG, Max: 5MB)
   - Upload Foto Tempat Usaha (Format: JPG/PNG, Max: 5MB)
   - Klik "Simpan Perubahan"

3. **Lihat Hasil**:
   - Preview foto muncul di bawah input field
   - File tersimpan di `storage/app/public/umkm/ktp/` dan `storage/app/public/umkm/tempat/`
   - Accessible via URL: `asset('storage/umkm/ktp/filename.jpg')`

### 📋 Validasi & Constraints

- **Format:** JPG, PNG, JPEG
- **Ukuran Maksimal:** 5MB (5120KB)
- **Upload Tipe:** Opsional (boleh dikosongkan)
- **Replace:** Upload baru akan menghapus file lama otomatis

### 📂 Storage Paths

```
public/storage/
├── umkm/
│   ├── ktp/           # Foto KTP Pemilik
│   └── tempat/        # Foto Tempat Usaha
```

### 🛠️ Troubleshooting

**Error: "File besar melebihi batas"**
- Pastikan file ≤ 5MB
- Compress image jika perlu

**Error: "Format tidak didukung"**
- Gunakan format JPG atau PNG
- Hindari format lain (GIF, BMP, TIFF, dll)

**Foto tidak tampil di preview**
- Pastikan symlink sudah dibuat: `php artisan storage:link`
- Cek folder `storage/app/public/` exists

**Upload gagal tanpa error message**
- Cek permission folder `storage/app/public/`
- Cek disk space tersedia

### ✅ Test Checklist

- [ ] Buka halaman Edit UMKM
- [ ] Upload Foto KTP berhasil
- [ ] Upload Foto Tempat berhasil
- [ ] Preview muncul dengan benar
- [ ] File tersimpan di storage
- [ ] Upload file baru replace file lama
- [ ] Validation reject file > 5MB
- [ ] Validation reject format invalid
- [ ] Edit tanpa upload juga work (optional fields)

---

**Updated:** 24 April 2026
**Status:** Ready to Use ✅
