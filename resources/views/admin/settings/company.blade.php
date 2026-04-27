@extends('layouts.dashboard')

@section('title', 'Pengaturan Perusahaan')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan Perusahaan</li>
        </ol>
    </nav>
@endsection

@section('content')
<style>
    .page-header {
        margin-bottom: 32px;
    }
    .page-title {
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    .page-subtitle {
        color: #718096;
        font-size: 15px;
    }
    .settings-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    .settings-tabs {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
        background: #f7fafc;
    }
    .settings-tab {
        flex: 1;
        padding: 20px;
        text-align: center;
        border-bottom: 3px solid transparent;
        cursor: pointer;
        font-weight: 600;
        color: #718096;
        transition: all 0.3s ease;
    }
    .settings-tab.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: white;
    }
    .settings-content {
        padding: 40px;
    }
    .form-section {
        margin-bottom: 40px;
    }
    .form-section-title {
        font-size: 18px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
    }
    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 24px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .form-control-custom {
        padding: 12px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    .form-control-custom:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    textarea.form-control-custom {
        min-height: 100px;
        resize: vertical;
    }
    .logo-preview {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        object-fit: contain;
        border: 2px dashed #e2e8f0;
        padding: 10px;
        display: none;
    }
    .logo-preview.show {
        display: block;
    }
    .file-input-wrapper {
        position: relative;
        display: inline-block;
    }
    .file-input-wrapper input[type="file"] {
        display: none;
    }
    .file-input-label {
        display: inline-block;
        padding: 12px 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
    }
    .file-input-label:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }
    .form-help-text {
        font-size: 12px;
        color: #718096;
        margin-top: 6px;
    }
    .btn-submit {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border: none;
        padding: 12px 40px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3);
    }
    .alert-success {
        background: linear-gradient(135deg, #d4edda, #e8f5e9);
        border: 1px solid #90caf9;
        color: #155724;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title"><i class="bi bi-gear me-2"></i>Pengaturan Perusahaan</h1>
    <p class="page-subtitle">Kelola informasi profil perusahaan yang akan ditampilkan di website</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan:</h6>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="settings-container">
    <!-- Tabs -->
    <div class="settings-tabs">
        <div class="settings-tab active" onclick="showTab('general')">
            <i class="bi bi-info-circle me-2"></i>Informasi Umum
        </div>
        <div class="settings-tab" onclick="showTab('seo')">
            <i class="bi bi-search me-2"></i>SEO & Meta
        </div>
        <div class="settings-tab" onclick="showTab('social')">
            <i class="bi bi-share me-2"></i>Media Sosial
        </div>
    </div>

    <!-- Content -->
    <div class="settings-content">
        <form action="{{ route('admin.settings.company.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- General Tab -->
            <div id="tab-general" class="tab-content">
                <!-- Company Branding -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-shop me-2"></i>Identitas Perusahaan</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Perusahaan</label>
                            <input type="text" name="company_name" class="form-control-custom" 
                                   value="{{ $settings['company_name']->value ?? 'Karang Taruna Teluknaga' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email Perusahaan</label>
                            <input type="email" name="company_email" class="form-control-custom" 
                                   value="{{ $settings['company_email']->value ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="tel" name="company_phone" class="form-control-custom" 
                                   value="{{ $settings['company_phone']->value ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Logo Perusahaan</label>
                            <div class="file-input-wrapper">
                                <label class="file-input-label">
                                    <i class="bi bi-upload me-2"></i>Pilih Logo
                                    <input type="file" name="company_logo" accept="image/*" onchange="previewLogo(event)">
                                </label>
                            </div>
                            @if($settings['company_logo']->value ?? null)
                                <img src="{{ asset($settings['company_logo']->value) }}" alt="Logo" class="logo-preview show mt-3">
                            @else
                                <img id="logoPreview" alt="Logo Preview" class="logo-preview mt-3">
                            @endif
                            <p class="form-help-text">Format: JPG, PNG, GIF (Max 2MB)</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Alamat Perusahaan</label>
                            <textarea name="company_address" class="form-control-custom" required>{{ $settings['company_address']->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Deskripsi Perusahaan</label>
                            <textarea name="company_description" class="form-control-custom" required>{{ $settings['company_description']->value ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Website Content -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-globe me-2"></i>Konten Website</h3>
                    
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Visi Perusahaan</label>
                            <textarea name="company_vision" class="form-control-custom">{{ $settings['company_vision']->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Misi Perusahaan</label>
                            <textarea name="company_mission" class="form-control-custom">{{ $settings['company_mission']->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Judul Hero Section</label>
                            <input type="text" name="company_hero_title" class="form-control-custom" 
                                   value="{{ $settings['company_hero_title']->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Subtitle Hero Section</label>
                            <input type="text" name="company_hero_subtitle" class="form-control-custom" 
                                   value="{{ $settings['company_hero_subtitle']->value ?? '' }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Teks Footer</label>
                            <textarea name="company_footer_text" class="form-control-custom">{{ $settings['company_footer_text']->value ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO Tab -->
            <div id="tab-seo" class="tab-content" style="display: none;">
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-search me-2"></i>SEO Settings</h3>
                    
                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="seo_title" class="form-control-custom" 
                                   value="{{ $settings['seo_title']->value ?? '' }}" maxlength="255">
                            <p class="form-help-text">Panjang ideal: 50-60 karakter</p>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group" style="grid-column: span 2;">
                            <label class="form-label">Meta Description</label>
                            <textarea name="seo_description" class="form-control-custom" maxlength="500">{{ $settings['seo_description']->value ?? '' }}</textarea>
                            <p class="form-help-text">Panjang ideal: 150-160 karakter</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Tab -->
            <div id="tab-social" class="tab-content" style="display: none;">
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-share me-2"></i>Media Sosial</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-facebook me-2"></i>Facebook URL</label>
                            <input type="url" name="social_facebook" class="form-control-custom" 
                                   value="{{ $settings['social_facebook']->value ?? '' }}" placeholder="https://facebook.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-instagram me-2"></i>Instagram URL</label>
                            <input type="url" name="social_instagram" class="form-control-custom" 
                                   value="{{ $settings['social_instagram']->value ?? '' }}" placeholder="https://instagram.com/...">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-twitter me-2"></i>Twitter URL</label>
                            <input type="url" name="social_twitter" class="form-control-custom" 
                                   value="{{ $settings['social_twitter']->value ?? '' }}" placeholder="https://twitter.com/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="bi bi-youtube me-2"></i>YouTube URL</label>
                            <input type="url" name="social_youtube" class="form-control-custom" 
                                   value="{{ $settings['social_youtube']->value ?? '' }}" placeholder="https://youtube.com/...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div style="display: flex; gap: 12px; margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 30px;">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save"></i>Simpan Perubahan
                </button>
                <a href="{{ route('dashboard.admin') }}" class="btn-submit" style="background: #cbd5e0; color: white; text-decoration: none;">
                    <i class="bi bi-arrow-left"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        
        // Remove active class from all tabs
        document.querySelectorAll('.settings-tab').forEach(el => el.classList.remove('active'));
        
        // Show selected tab
        document.getElementById('tab-' + tabName).style.display = 'block';
        
        // Add active class to clicked tab
        event.target.closest('.settings-tab').classList.add('active');
    }

    function previewLogo(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logoPreview');
                preview.src = e.target.result;
                preview.classList.add('show');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
