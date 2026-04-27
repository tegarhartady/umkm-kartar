# 🚀 QUICK START - Sistem Pengaturan Perusahaan

## 1️⃣ Akses Settings Panel

**URL:** `http://localhost:8000/admin/settings/company`

Atau klik menu **Pengaturan** di sidebar dashboard admin.

## 2️⃣ Isi Semua Pengaturan

Pengaturan terbagi dalam 3 tab:

### Tab 1: Informasi Umum
- **Nama Perusahaan** - Nama organisasi Anda
- **Email** - Email kontak
- **Telepon** - Nomor telepon
- **Logo** - Upload gambar logo (JPG, PNG, GIF max 2MB)
- **Alamat** - Alamat lengkap
- **Deskripsi** - Deskripsi singkat perusahaan
- **Visi** - Visi perusahaan (optional)
- **Misi** - Misi perusahaan (optional)
- **Judul Hero** - Judul di halaman utama
- **Subtitle Hero** - Subtitle di halaman utama
- **Teks Footer** - Text yang muncul di footer

### Tab 2: SEO & Meta
- **Meta Title** - Title untuk Google (50-60 karakter ideal)
- **Meta Description** - Deskripsi untuk Google (150-160 karakter ideal)

### Tab 3: Media Sosial
- **Facebook** - Link ke halaman Facebook
- **Instagram** - Link ke halaman Instagram
- **Twitter** - Link ke akun Twitter
- **YouTube** - Link ke channel YouTube

## 3️⃣ Simpan Perubahan

Klik tombol **"Simpan Perubahan"** di bawah form.

Sistem akan menampilkan notifikasi hijau ✅ jika berhasil.

## 4️⃣ Gunakan di Website

Setelah mengisi pengaturan, data akan otomatis muncul di website melalui helper function.

### Contoh di Homepage

Edit file `resources/views/home.blade.php` dan tambahkan:

```blade
<!-- Display Nama Perusahaan -->
<h1>{{ \App\Helpers\SettingHelper::get('company_name', 'UMKM Kami') }}</h1>

<!-- Display Logo -->
@php $logo = \App\Helpers\SettingHelper::get('company_logo'); @endphp
@if($logo)
    <img src="{{ asset($logo) }}" alt="Logo">
@endif

<!-- Display Deskripsi -->
<p>{{ \App\Helpers\SettingHelper::get('company_description') }}</p>

<!-- Display Visi & Misi -->
<h3>Visi</h3>
<p>{{ \App\Helpers\SettingHelper::get('company_vision') }}</p>

<h3>Misi</h3>
<p>{{ \App\Helpers\SettingHelper::get('company_mission') }}</p>

<!-- Display Footer Text -->
<footer>
    <p>{{ \App\Helpers\SettingHelper::get('company_footer_text') }}</p>
</footer>
```

### Contoh di Header/Navbar

```blade
<header>
    <nav>
        @php $logo = \App\Helpers\SettingHelper::get('company_logo'); @endphp
        @if($logo)
            <img src="{{ asset($logo) }}" alt="Logo" height="50">
        @else
            {{ \App\Helpers\SettingHelper::get('company_name', 'UMKM') }}
        @endif
    </nav>
</header>
```

### Contoh di Meta Tags

```blade
<head>
    <title>{{ \App\Helpers\SettingHelper::get('seo_title', 'Website Title') }}</title>
    <meta name="description" content="{{ \App\Helpers\SettingHelper::get('seo_description', 'Description') }}">
</head>
```

## 5️⃣ Struktur Helper Command

### Basic Usage
```blade
{!! \App\Helpers\SettingHelper::get('key_name') !!}
```

### Dengan Default Value
```blade
{{ \App\Helpers\SettingHelper::get('key_name', 'Default jika tidak ada') }}
```

### Di Controller
```php
use App\Helpers\SettingHelper;

$name = SettingHelper::get('company_name');
```

## 📋 Daftar Semua Key

```
company_name                 → Nama Perusahaan
company_email               → Email
company_phone               → Telepon
company_address            → Alamat
company_description        → Deskripsi
company_logo              → Logo (path file)
company_mission           → Misi
company_vision            → Visi
company_hero_title        → Judul Hero
company_hero_subtitle     → Subtitle Hero
company_footer_text       → Text Footer
seo_title                 → Meta Title
seo_description           → Meta Description
social_facebook           → Facebook URL
social_instagram          → Instagram URL
social_twitter            → Twitter URL
social_youtube            → YouTube URL
```

## 🎯 Contoh Real Implementation

### Homepage Hero Section
```blade
<section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <h1>{{ \App\Helpers\SettingHelper::get('company_hero_title', 'Welcome') }}</h1>
    <p>{{ \App\Helpers\SettingHelper::get('company_hero_subtitle', 'Subtitle') }}</p>
</section>
```

### Contact Info Box
```blade
<div class="contact-info">
    <h2>Hubungi Kami</h2>
    <p>
        <strong>Telepon:</strong> {{ \App\Helpers\SettingHelper::get('company_phone', '-') }}<br>
        <strong>Email:</strong> {{ \App\Helpers\SettingHelper::get('company_email', '-') }}<br>
        <strong>Alamat:</strong> {{ \App\Helpers\SettingHelper::get('company_address', '-') }}
    </p>
</div>
```

### Social Media Links
```blade
<div class="social-links">
    @php
        $fb = \App\Helpers\SettingHelper::get('social_facebook');
        $ig = \App\Helpers\SettingHelper::get('social_instagram');
        $tw = \App\Helpers\SettingHelper::get('social_twitter');
        $yt = \App\Helpers\SettingHelper::get('social_youtube');
    @endphp
    
    @if($fb) <a href="{{ $fb }}">Facebook</a> @endif
    @if($ig) <a href="{{ $ig }}">Instagram</a> @endif
    @if($tw) <a href="{{ $tw }}">Twitter</a> @endif
    @if($yt) <a href="{{ $yt }}">YouTube</a> @endif
</div>
```

## ❓ FAQ

**Q: Apakah perubahan langsung tampil di website?**
A: Ya! Setiap perubahan di dashboard langsung tersimpan dan tampil di website.

**Q: Berapa ukuran maksimal logo?**
A: Maksimal 2MB, format: JPG, PNG, atau GIF.

**Q: Bisa apa jika tidak isi semua field?**
A: Bisa! Field yang tidak diisi akan kosong. Gunakan default value di helper untuk fallback.

**Q: Bagaimana jika lupa mengisi setting?**
A: Gunakan parameter kedua di helper:
```blade
{{ \App\Helpers\SettingHelper::get('company_name', 'UMKM Default') }}
```
Text "UMKM Default" akan tampil jika setting kosong.

**Q: Bisa upload file selain gambar?**
A: Tidak, hanya gambar (JPG, PNG, GIF). Ini adalah file logo khusus.

**Q: Di mana logo tersimpan?**
A: Di folder `public/uploads/company/`

**Q: Bisa menambah pengaturan baru?**
A: Ya! Tambahkan input field baru di form, dan gunakan di helper dengan key yang sama.

## 🔄 Update Flow

```
Admin mengisi form → Click "Simpan" → Data tersimpan ke database → 
Helper function mengambil data → Muncul di website
```

## 🛠️ Troubleshooting

**Problem: Pengaturan tidak tersimpan**
- Pastikan tidak ada error message
- Check browser console untuk error
- Pastikan user sudah login sebagai admin

**Problem: Logo tidak muncul**
- Pastikan folder `public/uploads/company/` ada
- Check permission folder (chmod 755)
- Pastikan format file benar (JPG, PNG, GIF)

**Problem: Helper function tidak bekerja**
- Jalankan: `composer dump-autoload`
- Restart server
- Clear browser cache

---

**Selamat! Sistem pengaturan perusahaan Anda sudah siap digunakan!** 🎉

Untuk dokumentasi lengkap, baca file `SETTINGS_DOCUMENTATION.md`
