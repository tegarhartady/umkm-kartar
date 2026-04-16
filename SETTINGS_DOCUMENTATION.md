# 📋 Sistem Pengaturan Perusahaan (Settings Management)

## Overview
Sistem ini memungkinkan admin untuk mengatur dan mengubah konten perusahaan secara dinamis melalui dashboard, tanpa perlu mengubah kode aplikasi.

## Fitur Utama

### 1. **Informasi Umum**
- Nama Perusahaan
- Email Perusahaan
- Nomor Telepon
- Logo Perusahaan (upload gambar)
- Alamat Perusahaan
- Deskripsi Perusahaan
- Visi Perusahaan
- Misi Perusahaan
- Judul & Subtitle Hero Section
- Teks Footer

### 2. **SEO & Meta**
- Meta Title (untuk search engine)
- Meta Description

### 3. **Media Sosial**
- Facebook URL
- Instagram URL
- Twitter URL
- YouTube URL

## Struktur Database

### Table: `settings`
```sql
- id (Primary Key)
- key (string, unique) - Nama kunci pengaturan
- value (longText) - Nilai pengaturan
- group (string) - Kategori pengaturan (company, general, seo)
- created_at (timestamp)
- updated_at (timestamp)
```

## Arsitektur

### Model
- `App\Models\Setting` - Model untuk tabel settings

### Controller
- `App\Http\Controllers\Admin\SettingController` - Menangani logika CRUD settings

### Helper
- `App\Helpers\SettingHelper` - Helper function untuk mengakses settings

### Routes
```
GET  /admin/settings/company        - Tampilkan form pengaturan
POST /admin/settings/company        - Update pengaturan
```

## Cara Menggunakan

### Di Controller
```php
use App\Helpers\SettingHelper;

$companyName = SettingHelper::get('company_name', 'Default Name');
$settings = SettingHelper::getByGroup('company');
```

### Di View/Blade
```blade
<!-- Tampilkan nama perusahaan -->
<h1>{{ \App\Helpers\SettingHelper::get('company_name', 'Karang Taruna') }}</h1>

<!-- Tampilkan logo -->
<img src="{{ asset(\App\Helpers\SettingHelper::get('company_logo')) }}" alt="Logo">

<!-- Tampilkan deskripsi -->
<p>{{ \App\Helpers\SettingHelper::get('company_description') }}</p>
```

### Di Blade File Secara Langsung
Karena helper sudah di-autoload, Anda bisa menggunakan shortcut helper:

```blade
<!-- Shortcut lebih ringkas -->
@php
    $companyName = \App\Helpers\SettingHelper::get('company_name');
@endphp
```

## Implementasi pada Homepage/Public Pages

Update file `resources/views/home.blade.php` untuk menggunakan settings:

```blade
@extends('layouts.app')

@section('content')
<div class="hero-section" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <h1>{{ \App\Helpers\SettingHelper::get('company_hero_title', 'Selamat Datang') }}</h1>
    <p>{{ \App\Helpers\SettingHelper::get('company_hero_subtitle') }}</p>
</div>

<section class="company-info">
    <div class="container">
        <h2>Tentang Kami</h2>
        <p>{{ \App\Helpers\SettingHelper::get('company_description') }}</p>
        
        <div class="row mt-5">
            <div class="col-md-6">
                <h3>Visi</h3>
                <p>{{ \App\Helpers\SettingHelper::get('company_vision') }}</p>
            </div>
            <div class="col-md-6">
                <h3>Misi</h3>
                <p>{{ \App\Helpers\SettingHelper::get('company_mission') }}</p>
            </div>
        </div>
    </div>
</section>

<footer>
    <p>{{ \App\Helpers\SettingHelper::get('company_footer_text', 'Hak Cipta 2026') }}</p>
</footer>
@endsection
```

## Keuntungan Sistem Ini

✅ **Fleksibel** - Admin bisa mengubah konten tanpa perlu mengubah kode
✅ **Scalable** - Mudah menambah pengaturan baru hanya dengan menambah field di form
✅ **Aman** - Semua setting ter-store di database, tidak ada hardcoding
✅ **Efficient** - Caching bisa dengan mudah diimplementasikan
✅ **User-friendly** - Interface yang intuitif dengan tab organization
✅ **Responsive** - Form responsive di semua ukuran layar
✅ **Image Upload** - Support untuk upload logo perusahaan

## Penambahan Setting Baru

Jika ingin menambah setting baru:

1. **Tambahkan field di form** (`resources/views/admin/settings/company.blade.php`)
2. **Tambahkan ke validation** di `SettingController@updateCompany`
3. **Gunakan di tempat yang diperlukan** dengan `SettingHelper::get('key_name')`

Contoh:
```php
// 1. Di form
<input type="text" name="company_phone_alt" class="form-control-custom" 
       value="{{ $settings['company_phone_alt']->value ?? '' }}">

// 2. Di validation (sudah handle dengan loop $validated)

// 3. Di view
{{ \App\Helpers\SettingHelper::get('company_phone_alt') }}
```

## Fitur Mendatang

- [ ] Caching untuk performance
- [ ] Soft delete untuk backup settings
- [ ] Revision history untuk tracking changes
- [ ] Multi-language support
- [ ] Settings untuk social media analytics
- [ ] Settings untuk email templates
- [ ] Settings untuk halaman statis

## Troubleshooting

### Setting tidak muncul di form
- Pastikan helper di-autoload dengan `composer dump-autoload`
- Check apakah key-nya benar

### Logo tidak muncul
- Pastikan folder `public/uploads/company` sudah ada
- Check permission folder

### Setting tidak tersimpan
- Pastikan user sudah login dan punya role admin
- Check Laravel logs untuk error message
