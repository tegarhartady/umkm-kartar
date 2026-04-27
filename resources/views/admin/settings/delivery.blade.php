@extends('layouts.dashboard')

@section('title', 'Pengaturan Pengiriman')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan Pengiriman</li>
        </ol>
    </nav>
@endsection

@section('content')
<style>
    .page-header { margin-bottom: 32px; }
    .page-title {
        font-size: 28px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea, #764ba2);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 8px;
    }
    .page-subtitle { color: #718096; font-size: 15px; }
    .settings-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.06);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }
    .settings-content { padding: 40px; }
    .form-section { margin-bottom: 40px; }
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
    .form-group { display: flex; flex-direction: column; }
    .form-label { font-weight: 600; color: #2d3748; margin-bottom: 8px; font-size: 14px; }
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
    .switch-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 16px; width: 16px;
        left: 4px; bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider { background-color: #667eea; }
    input:checked + .slider:before { transform: translateX(26px); }
</style>

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-truck me-2"></i>Master Pengiriman</h1>
    <p class="page-subtitle">Atur biaya pengiriman dan metode distribusi produk UMKM</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

<div class="settings-container">
    <div class="settings-content">
        <form action="{{ route('admin.settings.delivery.update') }}" method="POST">
            @csrf

            <!-- Activation Section -->
            <div class="form-section">
                <h3 class="form-section-title"><i class="bi bi-toggle-on me-2"></i>Status Layanan Antar</h3>
                <div class="switch-wrapper">
                    <label class="switch">
                        <input type="hidden" name="delivery_enabled" value="0">
                        <input type="checkbox" name="delivery_enabled" value="1" {{ ($settings['delivery_enabled']->value ?? '0') == '1' ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span class="form-label mb-0">Aktifkan Pilihan "Diantar" di Checkout</span>
                </div>
                <p class="form-help-text text-muted">Jika dinonaktifkan, pembeli hanya bisa memilih metode "Ambil Sendiri (Take Away)".</p>
            </div>

            <!-- Pricing Section -->
            <div class="form-section" id="pricingSection">
                <h3 class="form-section-title"><i class="bi bi-cash-stack me-2"></i>Pengaturan Biaya</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tipe Biaya</label>
                        <select name="delivery_fee_type" class="form-control-custom" onchange="toggleFeeInputs(this.value)">
                            <option value="flat" {{ ($settings['delivery_fee_type']->value ?? '') == 'flat' ? 'selected' : '' }}>Biaya Flat (Tetap)</option>
                            <option value="distance" {{ ($settings['delivery_fee_type']->value ?? '') == 'distance' ? 'selected' : '' }}>Berdasarkan Jarak (per KM)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row" id="flatInput" style="{{ ($settings['delivery_fee_type']->value ?? 'flat') == 'flat' ? '' : 'display: none;' }}">
                    <div class="form-group">
                        <label class="form-label">Biaya Pengiriman Flat (Rp)</label>
                        <input type="number" name="delivery_fee_flat" class="form-control-custom" 
                               value="{{ $settings['delivery_fee_flat']->value ?? '0' }}" placeholder="Contoh: 10000">
                    </div>
                </div>

                <div class="form-row" id="distanceInputs" style="{{ ($settings['delivery_fee_type']->value ?? 'flat') == 'distance' ? '' : 'display: none;' }}">
                    <div class="form-group">
                        <label class="form-label">Biaya per Kilometer (Rp)</label>
                        <input type="number" name="delivery_fee_per_km" class="form-control-custom" 
                               value="{{ $settings['delivery_fee_per_km']->value ?? '0' }}" placeholder="Contoh: 2000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jarak Minimum (KM)</label>
                        <input type="number" name="delivery_min_distance" class="form-control-custom" 
                               value="{{ $settings['delivery_min_distance']->value ?? '1' }}" placeholder="Contoh: 1">
                        <p class="form-help-text">Biaya per KM akan dihitung setelah jarak melebihi batas ini.</p>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div style="display: flex; gap: 12px; margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 30px;">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-save"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleFeeInputs(type) {
        const flat = document.getElementById('flatInput');
        const distance = document.getElementById('distanceInputs');
        if (type === 'flat') {
            flat.style.display = 'block';
            distance.style.display = 'none';
        } else {
            flat.style.display = 'none';
            distance.style.display = 'flex';
        }
    }
</script>
@endsection
