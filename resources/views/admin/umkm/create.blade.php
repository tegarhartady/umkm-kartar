@extends('layouts.dashboard')

@section('title', 'Tambah UMKM')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard.admin') }}"><i class="bi bi-house me-1"></i>Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.umkm.index') }}">UMKM</a></li>
            <li class="breadcrumb-item active">Tambah UMKM</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="py-5" style="background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%); min-height: 100vh;">
    <div class="container">
        <div class="card shadow-lg border-0" style="border-radius: 20px; max-width: 700px; margin: 0 auto;">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 20px 20px 0 0; border: none;">
                <h2 class="mb-2 text-white"><i class="bi bi-shop me-3"></i>Daftar UMKM Baru</h2>
                <p class="mb-0 small">Lengkapi informasi UMKM untuk pendaftaran</p>
            </div>
            
            <div class="card-body p-5">
                <style>
                    .step-indicator {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin-bottom: 2rem;
                        flex-wrap: wrap;
                        gap: 1rem;
                    }
                    .step-item {
                        display: flex;
                        align-items: center;
                        gap: 0.5rem;
                    }
                    .step-dot {
                        width: 40px;
                        height: 40px;
                        border-radius: 50%;
                        background: #dee2e6;
                        color: #6c757d;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-weight: bold;
                        transition: all 0.3s ease;
                    }
                    .step-dot.active {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white;
                        transform: scale(1.1);
                        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
                    }
                    .step-dot.completed {
                        background: #28a745;
                        color: white;
                    }
                    .step-line {
                        width: 60px;
                        height: 2px;
                        background: #dee2e6;
                    }
                    .step-line.completed {
                        background: #28a745;
                    }
                    .wizard-step {
                        display: none;
                        animation: fadeIn 0.3s ease;
                    }
                    .wizard-step.active {
                        display: block;
                    }
                    @keyframes fadeIn {
                        from {
                            opacity: 0;
                            transform: translateY(10px);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }
                    .form-section-title {
                        color: #495057;
                        font-size: 1.1rem;
                        font-weight: 600;
                        margin-bottom: 1.5rem;
                        text-align: center;
                    }
                    .btn-wizard {
                        padding: 0.75rem 1.5rem;
                        font-weight: 500;
                        border: none;
                        border-radius: 10px;
                        transition: all 0.3s ease;
                    }
                    .btn-primary.btn-wizard {
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
                    }
                    .btn-primary.btn-wizard:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.35);
                    }
                    .btn-success.btn-wizard {
                        background: linear-gradient(135deg, #11c980 0%, #0d9d5f 100%);
                        box-shadow: 0 6px 20px rgba(17, 201, 128, 0.25);
                    }
                    .btn-success.btn-wizard:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 10px 30px rgba(17, 201, 128, 0.35);
                    }
                </style>
                <div class="step-indicator">
                    <div class="step-item">
                        <div class="step-dot active" id="step-1">1</div>
                        <span class="ms-2 d-none d-sm-inline text-muted">Info Dasar</span>
                    </div>
                    <div class="step-line" id="line-1"></div>
                    <div class="step-item">
                        <div class="step-dot" id="step-2">2</div>
                        <span class="ms-2 d-none d-sm-inline text-muted">Kontak & Lokasi</span>
                    </div>
                    <div class="step-line" id="line-2"></div>
                    <div class="step-item">
                        <div class="step-dot" id="step-3">3</div>
                        <span class="ms-2 d-none d-sm-inline text-muted">Detail Bisnis</span>
                    </div>
                </div>

                <form action="{{ route('admin.umkm.store') }}" method="POST" id="umkmForm">
                    @csrf
                    
                    <!-- Step 1: Info Dasar -->
                    <div class="wizard-step active" id="step-content-1">
                        <h4 class="form-section-title">
                            <i class="bi bi-building me-2 text-primary"></i>Informasi Dasar UMKM
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="nama_toko" class="form-control @error('nama_toko') is-invalid @enderror" 
                                           id="nama_toko" value="{{ old('nama_toko') }}" placeholder="Nama Toko" required>
                                    <label for="nama_toko">Nama Toko *</label>
                                    @error('nama_toko')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="pemilik" class="form-control @error('pemilik') is-invalid @enderror" 
                                           id="pemilik" value="{{ old('pemilik') }}" placeholder="Nama Pemilik" required>
                                    <label for="pemilik">Nama Pemilik *</label>
                                    @error('pemilik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" 
                                    id="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Hasil Laut" {{ old('kategori') == 'Hasil Laut' ? 'selected' : '' }}>Hasil Laut</option>
                                <option value="Makanan Olahan" {{ old('kategori') == 'Makanan Olahan' ? 'selected' : '' }}>Makanan Olahan</option>
                                <option value="Bumbu Dapur" {{ old('kategori') == 'Bumbu Dapur' ? 'selected' : '' }}>Bumbu Dapur</option>
                                <option value="Kerajinan" {{ old('kategori') == 'Kerajinan' ? 'selected' : '' }}>Kerajinan</option>
                                <option value="Kuliner" {{ old('kategori') == 'Kuliner' ? 'selected' : '' }}>Kuliner</option>
                            </select>
                            <label for="kategori">Kategori Usaha *</label>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Step 2: Kontak & Lokasi -->
                    <div class="wizard-step" id="step-content-2">
                        <h4 class="form-section-title">
                            <i class="bi bi-telephone me-2 text-primary"></i>Kontak & Lokasi
                        </h4>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" value="{{ old('email') }}" placeholder="Email" required>
                                    <label for="email">Email *</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" value="{{ old('phone') }}" placeholder="Nomor Telepon" required>
                                    <label for="phone">Nomor Telepon *</label>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="desa" class="form-select @error('desa') is-invalid @enderror" 
                                            id="desa" required>
                                        <option value="">Pilih Desa</option>
                                        <option value="Teluknaga" {{ old('desa') == 'Teluknaga' ? 'selected' : '' }}>Teluknaga</option>
                                        <option value="Tanjung Pasir" {{ old('desa') == 'Tanjung Pasir' ? 'selected' : '' }}>Tanjung Pasir</option>
                                        <option value="Muara" {{ old('desa') == 'Muara' ? 'selected' : '' }}>Muara</option>
                                        <option value="Lemo" {{ old('desa') == 'Lemo' ? 'selected' : '' }}>Lemo</option>
                                        <option value="Pangkalan" {{ old('desa') == 'Pangkalan' ? 'selected' : '' }}>Pangkalan</option>
                                    </select>
                                    <label for="desa">Desa *</label>
                                    @error('desa')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-floating">
                            <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" placeholder="Alamat Lengkap" style="height: 120px" required>{{ old('alamat') }}</textarea>
                            <label for="alamat">Alamat Lengkap *</label>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Step 3: Detail Bisnis -->
                    <div class="wizard-step" id="step-content-3">
                        <h4 class="form-section-title">
                            <i class="bi bi-briefcase me-2 text-primary"></i>Detail Bisnis
                        </h4>
                        
                        <div class="form-floating mb-3">
                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" placeholder="Deskripsi UMKM" style="height: 120px">{{ old('deskripsi') }}</textarea>
                            <label for="deskripsi">Deskripsi UMKM</label>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating">
                            <input type="number" name="omzet_bulanan" class="form-control @error('omzet_bulanan') is-invalid @enderror" 
                                   id="omzet_bulanan" value="{{ old('omzet_bulanan') }}" placeholder="Omzet Bulanan">
                            <label for="omzet_bulanan">Omzet Bulanan (Rp)</label>
                            @error('omzet_bulanan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi:</strong> UMKM yang didaftarkan akan berstatus "Pending" dan perlu persetujuan admin.
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-secondary btn-wizard" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                            <i class="bi bi-arrow-left me-2"></i>Sebelumnya
                        </button>
                        <div class="ms-auto">
                            <button type="button" class="btn btn-primary btn-wizard" id="nextBtn" onclick="changeStep(1)">
                                Selanjutnya <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                            <button type="submit" class="btn btn-success btn-wizard" id="submitBtn" style="display: none;">
                                <i class="bi bi-check me-2"></i>Daftar UMKM
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-wizard {
    background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
    min-height: 100vh;
    padding: 2rem 0;
}
.wizard-card {
    background: white;
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    max-width: 700px;
    margin: 0 auto;
}
.wizard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem 2rem;
    text-align: center;
}
.wizard-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.wizard-header p {
    font-size: 0.95rem;
    opacity: 0.95;
    margin-bottom: 0;
}
.wizard-body {
    padding: 3rem 2.5rem;
}
.form-floating {
    margin-bottom: 1.5rem;
}
.form-floating > .form-control {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 0.75rem;
    transition: all 0.3s ease;
}
.form-floating > .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}
.form-floating > .form-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1rem 0.75rem;
    transition: all 0.3s ease;
}
.form-floating > .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
}
.form-floating > label {
    color: #718096;
    font-weight: 500;
}
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label,
.form-floating > .form-select:focus ~ label,
.form-floating > .form-select:not(:first-option) ~ label {
    color: #667eea;
}
.wizard-step {
    display: none;
    animation: fadeIn 0.3s ease;
}
.wizard-step.active {
    display: block;
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.btn-wizard {
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border: none;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.btn-primary.btn-wizard {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.25);
}
.btn-primary.btn-wizard:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.35);
}
.btn-success.btn-wizard {
    background: linear-gradient(135deg, #11c980 0%, #0d9d5f 100%);
    box-shadow: 0 6px 20px rgba(17, 201, 128, 0.25);
}
.btn-success.btn-wizard:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(17, 201, 128, 0.35);
}
.btn-secondary.btn-wizard {
    background: #cbd5e0;
    color: white;
}
.step-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}
.step-item {
    display: flex;
    align-items: center;
    margin: 0.5rem 1rem;
}
.step-dot {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s ease;
}
.step-dot.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}
.step-dot.completed {
    background: #28a745;
    color: white;
}
.step-line {
    width: 80px;
    height: 2px;
    background: #dee2e6;
    margin: 0 10px;
}
.step-line.completed {
    background: #28a745;
}
.form-section-title {
    color: #495057;
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    text-align: center;
}
.alert-info {
    background: linear-gradient(135deg, #d4e8f7 0%, #e8f3ff 100%);
    border: 1px solid #90caf9;
    border-radius: 10px;
}
.invalid-feedback {
    display: block;
    color: #e74c3c;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
.is-invalid {
    border-color: #e74c3c !important;
}
</style>
@endpush

@push('scripts')
<script>
let currentStep = 1;
const totalSteps = 3;

function changeStep(direction) {
    if (direction === 1 && currentStep < totalSteps) {
        // Validate current step
        if (validateStep(currentStep)) {
            currentStep++;
            showStep();
        }
    } else if (direction === -1 && currentStep > 1) {
        currentStep--;
        showStep();
    }
}

function showStep() {
    // Hide all steps
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById(`step-content-${i}`).classList.remove('active');
        document.getElementById(`step-${i}`).classList.remove('active', 'completed');
        if (i < totalSteps) {
            document.getElementById(`line-${i}`).classList.remove('completed');
        }
    }
    
    // Show current step
    document.getElementById(`step-content-${currentStep}`).classList.add('active');
    document.getElementById(`step-${currentStep}`).classList.add('active');
    
    // Mark completed steps
    for (let i = 1; i < currentStep; i++) {
        document.getElementById(`step-${i}`).classList.add('completed');
        if (i < totalSteps) {
            document.getElementById(`line-${i}`).classList.add('completed');
        }
    }
    
    // Update navigation buttons
    document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'block';
    document.getElementById('nextBtn').style.display = currentStep === totalSteps ? 'none' : 'block';
    document.getElementById('submitBtn').style.display = currentStep === totalSteps ? 'block' : 'none';
}

function validateStep(step) {
    const requiredFields = {
        1: ['nama_toko', 'pemilik', 'kategori'],
        2: ['email', 'phone', 'desa', 'alamat'],
        3: [] // No required fields for step 3
    };
    
    const fields = requiredFields[step];
    let isValid = true;
    
    fields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

// Initialize
showStep();
</script>
@endpush
