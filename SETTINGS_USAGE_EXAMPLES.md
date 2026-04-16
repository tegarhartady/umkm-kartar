# Contoh Penggunaan Settings di Halaman Public

Setelah sistem settings selesai, Anda bisa menggunakannya di semua halaman public. Berikut beberapa contoh:

## 1. Homepage (resources/views/home.blade.php)

```blade
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 100px 0; text-align: center;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 20px;">
            {{ \App\Helpers\SettingHelper::get('company_hero_title', 'Selamat Datang') }}
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">
            {{ \App\Helpers\SettingHelper::get('company_hero_subtitle', 'Bergabunglah dengan ribuan UMKM kami') }}
        </p>
    </div>
</section>

<!-- About Section -->
<section class="about py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                @php $logo = \App\Helpers\SettingHelper::get('company_logo'); @endphp
                @if($logo)
                    <img src="{{ asset($logo) }}" alt="Logo" style="max-width: 100%; height: auto;">
                @else
                    <div style="width: 100%; height: 300px; background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-image" style="font-size: 80px; color: #ccc;"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <h2>Tentang {{ \App\Helpers\SettingHelper::get('company_name', 'Kami') }}</h2>
                <p>{{ \App\Helpers\SettingHelper::get('company_description', '') }}</p>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission -->
<section class="vision-mission py-5" style="background: #f7fafc;">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3><i class="bi bi-eye me-2"></i>Visi</h3>
                        <p>{{ \App\Helpers\SettingHelper::get('company_vision', '') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3><i class="bi bi-bullseye me-2"></i>Misi</h3>
                        <p>{{ \App\Helpers\SettingHelper::get('company_mission', '') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Info -->
<section class="contact-info py-5">
    <div class="container">
        <h2 class="text-center mb-5">Hubungi Kami</h2>
        <div class="row">
            <div class="col-md-4 text-center">
                <i class="bi bi-telephone" style="font-size: 40px; color: #667eea; margin-bottom: 15px; display: block;"></i>
                <h4>Telepon</h4>
                <p>{{ \App\Helpers\SettingHelper::get('company_phone', '-') }}</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="bi bi-envelope" style="font-size: 40px; color: #667eea; margin-bottom: 15px; display: block;"></i>
                <h4>Email</h4>
                <p>{{ \App\Helpers\SettingHelper::get('company_email', '-') }}</p>
            </div>
            <div class="col-md-4 text-center">
                <i class="bi bi-geo-alt" style="font-size: 40px; color: #667eea; margin-bottom: 15px; display: block;"></i>
                <h4>Alamat</h4>
                <p>{{ \App\Helpers\SettingHelper::get('company_address', '-') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
```

## 2. Header/Navigation (resources/views/layouts/app.blade.php)

```blade
<header>
    <nav class="navbar">
        <div class="container">
            <!-- Logo -->
            @php $logo = \App\Helpers\SettingHelper::get('company_logo'); @endphp
            <a href="/" class="navbar-brand">
                @if($logo)
                    <img src="{{ asset($logo) }}" alt="Logo" height="50">
                @else
                    <span class="brand-text">{{ \App\Helpers\SettingHelper::get('company_name', 'UMKM') }}</span>
                @endif
            </a>
            
            <!-- Navigation Items -->
            <ul class="navbar-menu">
                <li><a href="/">Home</a></li>
                <li><a href="/katalog">Katalog</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#contact">Kontak</a></li>
            </ul>
        </div>
    </nav>
</header>
```

## 3. Footer (resources/views/layouts/footer.blade.php)

```blade
<footer style="background: #2d3748; color: white; padding: 40px 0; margin-top: 60px;">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-3">
                <h5>{{ \App\Helpers\SettingHelper::get('company_name', 'Tentang') }}</h5>
                <p>{{ Str::limit(\App\Helpers\SettingHelper::get('company_description', ''), 200) }}</p>
            </div>
            <div class="col-md-3">
                <h5>Kontak</h5>
                <p>
                    📞 {{ \App\Helpers\SettingHelper::get('company_phone', '-') }}<br>
                    ✉️ {{ \App\Helpers\SettingHelper::get('company_email', '-') }}<br>
                    📍 {{ \App\Helpers\SettingHelper::get('company_address', '-') }}
                </p>
            </div>
            <div class="col-md-3">
                <h5>Menu</h5>
                <ul style="list-style: none; padding: 0;">
                    <li><a href="/" style="color: white; text-decoration: none;">Home</a></li>
                    <li><a href="/katalog" style="color: white; text-decoration: none;">Katalog</a></li>
                    <li><a href="/admin" style="color: white; text-decoration: none;">Dashboard</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h5>Follow Us</h5>
                <div style="display: flex; gap: 10px;">
                    @php
                        $facebook = \App\Helpers\SettingHelper::get('social_facebook');
                        $instagram = \App\Helpers\SettingHelper::get('social_instagram');
                        $twitter = \App\Helpers\SettingHelper::get('social_twitter');
                        $youtube = \App\Helpers\SettingHelper::get('social_youtube');
                    @endphp
                    @if($facebook)
                        <a href="{{ $facebook }}" target="_blank" style="color: white;"><i class="bi bi-facebook"></i></a>
                    @endif
                    @if($instagram)
                        <a href="{{ $instagram }}" target="_blank" style="color: white;"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if($twitter)
                        <a href="{{ $twitter }}" target="_blank" style="color: white;"><i class="bi bi-twitter"></i></a>
                    @endif
                    @if($youtube)
                        <a href="{{ $youtube }}" target="_blank" style="color: white;"><i class="bi bi-youtube"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <hr style="border-color: #4a5568;">
        <div style="text-align: center;">
            <p>{{ \App\Helpers\SettingHelper::get('company_footer_text', '© 2026 Karang Taruna Teluknaga. Hak Cipta Dilindungi.') }}</p>
        </div>
    </div>
</footer>
```

## 4. Meta Tags (resources/views/layouts/head.blade.php)

```blade
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Dynamic Meta Tags from Settings -->
    <title>{{ \App\Helpers\SettingHelper::get('seo_title', 'Karang Taruna Teluknaga - UMKM Terpadu') }}</title>
    <meta name="description" content="{{ \App\Helpers\SettingHelper::get('seo_description', 'Platform digital untuk UMKM di Teluknaga') }}">
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ \App\Helpers\SettingHelper::get('seo_title', 'Karang Taruna Teluknaga') }}">
    <meta property="og:description" content="{{ \App\Helpers\SettingHelper::get('seo_description', '') }}">
    @php $logo = \App\Helpers\SettingHelper::get('company_logo'); @endphp
    @if($logo)
        <meta property="og:image" content="{{ asset($logo) }}">
    @endif
    
    <!-- Other meta tags -->
    <meta name="keywords" content="UMKM, Teluknaga, E-commerce">
    <meta name="author" content="{{ \App\Helpers\SettingHelper::get('company_name', 'Karang Taruna') }}">
</head>
```

## 5. SEO & Structured Data

```blade
<!-- Di bagian head atau blade template -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "{{ \App\Helpers\SettingHelper::get('company_name', 'Karang Taruna') }}",
  "description": "{{ \App\Helpers\SettingHelper::get('company_description', '') }}",
  "logo": "{{ asset(\App\Helpers\SettingHelper::get('company_logo', '')) }}",
  "contact": {
    "@type": "ContactPoint",
    "telephone": "{{ \App\Helpers\SettingHelper::get('company_phone', '') }}",
    "contactType": "Customer Service"
  },
  "sameAs": [
    "{{ \App\Helpers\SettingHelper::get('social_facebook', '') }}",
    "{{ \App\Helpers\SettingHelper::get('social_instagram', '') }}",
    "{{ \App\Helpers\SettingHelper::get('social_twitter', '') }}",
    "{{ \App\Helpers\SettingHelper::get('social_youtube', '') }}"
  ]
}
</script>
```

## Quick Reference

```php
// Di mana saja di Blade file:
\App\Helpers\SettingHelper::get('key_name', 'default_value')

// Di Controller:
use App\Helpers\SettingHelper;
$value = SettingHelper::get('company_name', 'Default');

// Get semua settings company:
$settings = SettingHelper::getByGroup('company');
```

## Key Names yang Tersedia

```
company_name
company_description
company_email
company_phone
company_address
company_mission
company_vision
company_logo
company_hero_title
company_hero_subtitle
company_footer_text
seo_title
seo_description
social_facebook
social_instagram
social_twitter
social_youtube
```
