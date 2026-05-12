@extends('layouts.dashboard')

@section('title', 'Pengaturan Pembayaran')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item active">Pengaturan Pembayaran</li>
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
    .preview-container {
        margin-top: 15px;
        padding: 15px;
        border: 2px dashed #e2e8f0;
        border-radius: 12px;
        display: inline-block;
    }
    .qris-preview {
        max-width: 250px;
        height: auto;
        border-radius: 8px;
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
</style>

<div class="page-header">
    <h1 class="page-title"><i class="bi bi-wallet2 me-2"></i>Master Pembayaran</h1>
    <p class="page-subtitle">Atur rincian rekening bank dan QRIS untuk menerima pembayaran dari pelanggan</p>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle me-2"></i>Terjadi Kesalahan:</h6>
        <ul class="mb-0 small ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="settings-container">
    <div class="settings-content">
        <form action="{{ route('admin.settings.payment.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Transfer Bank Section -->
            <div class="form-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="form-section-title mb-0 border-0 pb-0"><i class="bi bi-bank me-2"></i>Informasi Rekening Bank (Manual)</h3>
                    <div class="form-check form-switch">
                        <input type="hidden" name="payment_manual_enabled" value="0">
                        <input class="form-check-input" type="checkbox" name="payment_manual_enabled" value="1" id="manual_enabled" {{ ($settings['payment_manual_enabled']->value ?? '1') == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                        <label class="form-check-label ms-2 fw-bold" for="manual_enabled">Aktifkan</label>
                    </div>
                </div>

                <div class="alert alert-light border mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">Daftar Rekening Bank</h6>
                        <p class="text-muted small mb-0">Klik tombol di samping untuk menambah rekening baru.</p>
                    </div>
                    <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addBankModal">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Rekening
                    </button>
                </div>

                <div class="table-responsive mb-4">
                    <table class="table table-hover align-middle border-top">
                        <thead class="table-light">
                            <tr>
                                <th>Bank</th>
                                <th>Nomor Rekening</th>
                                <th>Nama Pemilik</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($adminBanks as $bank)
                            <tr>
                                <td class="fw-600">{{ $bank->bank_name }}</td>
                                <td>{{ $bank->account_number }}</td>
                                <td>{{ $bank->account_holder }}</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="if(confirm('Hapus rekening ini?')) { document.getElementById('delete-bank-form').action = '{{ route('admin.settings.payment.bank.delete', $bank->id) }}'; document.getElementById('delete-bank-form').submit(); }">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4 italic">Belum ada rekening yang ditambahkan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <hr class="my-4">

                <!-- Legacy Single Settings (Keep for compatibility if needed, or remove) -->
                <div class="form-row d-none">
                    <div class="form-group">
                        <label class="form-label">Nama Bank (Default)</label>
                        <input type="text" name="payment_bank_name" class="form-control-custom" 
                               value="{{ $settings['payment_bank_name']->value ?? '' }}" placeholder="Contoh: BCA, Mandiri, BRI">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor Rekening (Default)</label>
                        <input type="text" name="payment_bank_account" class="form-control-custom" 
                               value="{{ $settings['payment_bank_account']->value ?? '' }}" placeholder="Contoh: 1234567890">
                    </div>
                </div>

                <div class="form-row d-none">
                    <div class="form-group">
                        <label class="form-label">Nama Pemilik Rekening (Default)</label>
                        <input type="text" name="payment_bank_holder" class="form-control-custom" 
                               value="{{ $settings['payment_bank_holder']->value ?? '' }}" placeholder="Nama sesuai di buku tabungan">
                    </div>
                </div>
                
                <!-- QRIS Section -->
                <div class="form-group">
                    <label class="form-label">Unggah Gambar QRIS</label>
                    <div class="mb-3">
                        <label class="file-input-label">
                            <i class="bi bi-upload me-2"></i>Pilih Gambar QRIS
                            <input type="file" name="payment_qris_image" accept="image/*" style="display: none;" onchange="previewQRIS(event)">
                        </label>
                    </div>
                    
                    <div class="preview-container" id="qrisPreviewContainer" style="{{ isset($settings['payment_qris_image']) ? '' : 'display: none;' }}">
                        <p class="form-label small text-muted mb-2">Pratinjau QRIS:</p>
                        <img id="qrisPreview" src="{{ isset($settings['payment_qris_image']) ? asset($settings['payment_qris_image']->value) : '' }}" class="qris-preview">
                    </div>
                    <p class="form-help-text mt-2">Gambar ini akan ditampilkan di halaman checkout saat pembeli memilih metode QRIS.</p>
                </div>
            </div>

            <!-- Midtrans Section -->
            <div class="form-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="form-section-title mb-0 border-0 pb-0"><i class="bi bi-shield-check me-2"></i>Midtrans Payment Gateway (Otomatis)</h3>
                    <div class="form-check form-switch">
                        <input type="hidden" name="payment_midtrans_enabled" value="0">
                        <input class="form-check-input" type="checkbox" name="payment_midtrans_enabled" value="1" id="midtrans_enabled" {{ ($settings['payment_midtrans_enabled']->value ?? '0') == '1' ? 'checked' : '' }} style="width: 3em; height: 1.5em;">
                        <label class="form-check-label ms-2 fw-bold" for="midtrans_enabled">Aktifkan</label>
                    </div>
                </div>
                <div class="alert alert-info py-2 mb-4 small">
                    <i class="bi bi-info-circle me-2"></i> Integrasi Midtrans memungkinkan pembayaran otomatis via VA, E-Wallet, dan Kartu Kredit.
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Midtrans Server Key</label>
                        <input type="password" name="midtrans_server_key" class="form-control-custom" 
                               value="{{ $settings['midtrans_server_key']->value ?? '' }}" placeholder="SB-Mid-server-...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Midtrans Client Key</label>
                        <input type="text" name="midtrans_client_key" class="form-control-custom" 
                               value="{{ $settings['midtrans_client_key']->value ?? '' }}" placeholder="SB-Mid-client-...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Environment</label>
                    <select name="midtrans_is_production" class="form-control-custom">
                        <option value="0" {{ ($settings['midtrans_is_production']->value ?? '0') == '0' ? 'selected' : '' }}>Sandbox (Testing)</option>
                        <option value="1" {{ ($settings['midtrans_is_production']->value ?? '1') == '1' ? 'selected' : '' }}>Production (Live)</option>
                    </select>
                    <p class="form-help-text mt-2">Gunakan Sandbox untuk uji coba transaksi sebelum menggunakan mode Production.</p>
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

<!-- Hidden form for bank deletion to avoid nested forms -->
<form id="delete-bank-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Add Bank Modal -->
<div class="modal fade" id="addBankModal" tabindex="-1" aria-labelledby="addBankModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addBankModalLabel">Tambah Rekening Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.settings.payment.bank.add') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Bank</label>
                        <select name="bank_name" class="form-select form-control-custom w-100" required>
                            <option value="">-- Pilih Bank --</option>
                            @foreach($masterBanks as $mb)
                                <option value="{{ $mb->nama_bank }}">{{ $mb->nama_bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Rekening</label>
                        <input type="text" name="account_number" class="form-control-custom w-100" placeholder="Contoh: 1234567890" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Pemilik Rekening</label>
                        <input type="text" name="account_holder" class="form-control-custom w-100" placeholder="Nama sesuai buku tabungan" required>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Rekening</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewQRIS(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('qrisPreview');
                const container = document.getElementById('qrisPreviewContainer');
                preview.src = e.target.result;
                container.style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
