@extends('layouts.app')

@section('title', 'Pesanan Berhasil - Karang Taruna Teluknaga')

@section('content')

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <!-- Success Card -->
                <div class="card border-0 shadow-lg">
                    <div class="card-body text-center p-5">
                        <!-- Success Icon -->
                        <div class="mb-4">
                            <div style="width: 80px; height: 80px; background: #198754; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                                <i class="bi bi-check-circle text-white" style="font-size: 3rem;"></i>
                            </div>
                        </div>

                        <!-- Success Message -->
                        <h2 class="fw-bold mb-2">Pesanan Berhasil Dibuat!</h2>
                        <p class="text-muted mb-4">Terima kasih telah berbelanja. Silakan lanjutkan pembayaran untuk mengonfirmasi pesanan Anda.</p>

                        <!-- Transaction Code -->
                        <div class="alert alert-info mb-4">
                            <strong>Kode Transaksi:</strong>
                            <div class="mt-2">
                                <code style="font-size: 1.2rem; background: #f8f9fa; padding: 10px; border-radius: 5px; display: inline-block;">
                                    {{ $transaction->transaction_code }}
                                </code>
                            </div>
                            <small class="text-muted d-block mt-2">Simpan kode ini untuk referensi pesanan Anda</small>
                        </div>

                        <!-- Order Details -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded shadow-sm">
                                    <small class="text-muted d-block mb-1">Produk</small>
                                    <p class="fw-bold mb-0">{{ $transaction->product->nama_produk }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded shadow-sm">
                                    <small class="text-muted d-block mb-1">Jenis & Distribusi</small>
                                    <p class="fw-bold mb-0">
                                        {{ $transaction->order_type === 'po' ? 'Pre-Order' : 'Langsung Kirim' }} 
                                        <span class="text-muted mx-1">|</span>
                                        {{ $transaction->delivery_type === 'delivery' ? 'Diantar' : 'Take Away' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded shadow-sm">
                                    <small class="text-muted d-block mb-1">Metode Bayar</small>
                                    <p class="fw-bold mb-0 text-uppercase">
                                        @if ($transaction->payment_method === 'transfer')
                                            Transfer Bank
                                        @elseif ($transaction->payment_method === 'qris')
                                            QRIS
                                        @elseif ($transaction->payment_method === 'ewallet')
                                            E-Wallet
                                        @else
                                            COD
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded shadow-sm">
                                    <small class="text-muted d-block mb-1">Biaya Kirim</small>
                                    <p class="fw-bold mb-0 {{ $transaction->delivery_fee > 0 ? 'text-primary' : 'text-success' }}">
                                        {{ $transaction->delivery_fee > 0 ? 'Rp' . number_format($transaction->delivery_fee, 0, ',', '.') : 'Gratis' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-success bg-opacity-10 rounded shadow-sm border border-success border-opacity-25">
                                    <small class="text-muted d-block mb-1 text-success">Total Tagihan</small>
                                    <p class="fw-bold mb-0 text-success fs-5">Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Midtrans Payment Button -->
                        @if($transaction->payment_method === 'midtrans' && $transaction->status === 'pending' && $transaction->snap_token)
                        <div class="card border-primary mb-4 shadow-sm overflow-hidden">
                            <div class="card-header bg-primary text-white text-center py-3">
                                <h6 class="fw-bold mb-0"><i class="bi bi-shield-lock me-2"></i>Selesaikan Pembayaran Anda</h6>
                            </div>
                            <div class="card-body text-center p-4">
                                <p class="mb-4 text-muted">Klik tombol di bawah untuk membayar menggunakan Virtual Account, E-Wallet, atau metode lainnya melalui Midtrans.</p>
                                <button id="pay-button" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm grow">
                                    <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                                </button>
                            </div>
                        </div>

                        @php
                            $isProduction = (\App\Models\Setting::where('key', 'midtrans_is_production')->first()->value ?? '0') == '1';
                            $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
                            $clientKey = \App\Models\Setting::where('key', 'midtrans_client_key')->first()->value ?? config('midtrans.client_key');
                        @endphp
                        <script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
                        <script type="text/javascript">
                            const payButton = document.getElementById('pay-button');
                            payButton.onclick = function() {
                                snap.pay('{{ $transaction->snap_token }}', {
                                    onSuccess: function(result) {
                                        window.location.reload();
                                    },
                                    onPending: function(result) {
                                        window.location.reload();
                                    },
                                    onError: function(result) {
                                        alert("Pembayaran gagal!");
                                    },
                                    onClose: function() {
                                        alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                                    }
                                });
                            };
                        </script>
                        @endif

                        <!-- QRIS Payment Display -->
                        @if($transaction->payment_method === 'qris')
                        <div class="card border-primary mb-4 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-qr-code-scan me-2"></i>Scan QRIS untuk Pembayaran</h6>
                                @php
                                    $qrisImage = \App\Models\Setting::where('key', 'payment_qris_image')->first();
                                @endphp
                                @if($qrisImage)
                                    <img src="{{ asset($qrisImage->value) }}" class="img-fluid rounded mb-3 shadow-sm" style="max-width: 300px; border: 1px solid #eee;">
                                    <div class="alert alert-info py-2 mb-0 small">
                                        <i class="bi bi-info-circle me-1"></i> Setelah scan dan bayar, mohon simpan bukti pembayaran Anda.
                                    </div>
                                @else
                                    <div class="alert alert-warning">QRIS Image not found. Please contact admin.</div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Bank Info for Transfer -->
                        @if($transaction->payment_method === 'transfer')
                        <div class="card border-info mb-4 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 text-info"><i class="bi bi-bank me-2"></i>Informasi Rekening Bank</h6>
                                @php
                                    $bankName = \App\Models\Setting::where('key', 'payment_bank_name')->first();
                                    $bankAcc = \App\Models\Setting::where('key', 'payment_bank_account')->first();
                                    $bankHolder = \App\Models\Setting::where('key', 'payment_bank_holder')->first();
                                @endphp
                                <div class="p-3 bg-info bg-opacity-10 rounded border border-info border-opacity-25 text-start">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <p class="mb-1 text-muted small">Bank {{ $bankName->value ?? '-' }}</p>
                                            <p class="mb-1 h4 fw-bold text-dark">{{ $bankAcc->value ?? '-' }}</p>
                                            <p class="mb-0 text-muted small">a.n {{ $bankHolder->value ?? '-' }}</p>
                                        </div>
                                        <div class="col-4 text-end">
                                            <button class="btn btn-sm btn-outline-info" onclick="copyToClipboard('{{ $bankAcc->value ?? '' }}')">Salin</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function copyToClipboard(text) {
                                navigator.clipboard.writeText(text).then(() => {
                                    alert('Nomor rekening berhasil disalin!');
                                });
                            }
                        </script>
                        @endif

                        <!-- Upload Payment Proof -->
                        @if(in_array($transaction->payment_method, ['transfer', 'qris']) && $transaction->status === 'pending')
                        <div class="card border-warning mb-4 shadow-sm overflow-hidden">
                            <div class="card-header bg-warning text-dark text-center py-3">
                                <h6 class="fw-bold mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Bukti Pembayaran</h6>
                            </div>
                            <div class="card-body p-4">
                                @if($transaction->payment_proof)
                                    <div class="alert alert-success mb-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> Bukti pembayaran telah diunggah. 
                                        <a href="{{ asset('storage/' . $transaction->payment_proof) }}" target="_blank" class="text-decoration-underline text-success fw-bold">Lihat Foto</a>
                                    </div>
                                    <p class="text-muted small mb-3">Anda dapat mengunggah ulang jika bukti sebelumnya salah atau kurang jelas.</p>
                                @else
                                    <p class="text-muted mb-4 small">Mohon unggah struk atau screenshot bukti transfer Anda untuk mempercepat proses verifikasi oleh admin.</p>
                                @endif

                                <form action="{{ route('transaction.upload_proof', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3 text-start">
                                        <label class="form-label small fw-bold">Pilih Foto Bukti Bayar</label>
                                        <input type="file" name="payment_proof" class="form-control" accept="image/*" required>
                                        <div class="form-text">Maksimal 2MB (JPG, PNG, JPEG)</div>
                                    </div>
                                    <button type="submit" class="btn btn-warning w-100 shadow-sm">
                                        <i class="bi bi-upload me-2"></i> {{ $transaction->payment_proof ? 'Ganti Bukti Bayar' : 'Kirim Bukti Pembayaran' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endif

                        <!-- Buyer Info -->
                        <div class="card bg-light border-0 mb-4 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 text-start"><i class="bi bi-geo-alt me-2 text-danger"></i>Alamat Pengiriman</h6>
                                <div class="text-start">
                                    <p class="mb-1"><strong>{{ $transaction->buyer_name }}</strong> <span class="text-muted mx-1">|</span> {{ $transaction->buyer_phone }}</p>
                                    <p class="mb-0 text-muted small">{{ $transaction->buyer_address }}, {{ $transaction->buyer_city }} {{ $transaction->buyer_postal_code }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="alert alert-warning mb-4 border-0 shadow-sm text-start">
                            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Instruksi Pembayaran:</h6>
                            <ol class="small mb-0 ps-3">
                                @if ($transaction->payment_method === 'qris')
                                    <li>Buka aplikasi e-wallet atau mobile banking Anda (Gopay, OVO, Dana, dll)</li>
                                    <li>Gunakan fitur <strong>Scan/Bayar</strong> dan arahkan ke kode QR di atas</li>
                                    <li>Masukkan nominal sesuai total tagihan: <strong>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</strong></li>
                                    <li>Kirim bukti pembayaran ke nomor WhatsApp admin untuk konfirmasi cepat</li>
                                @elseif ($transaction->payment_method === 'transfer')
                                    <li>Transfer ke nomor rekening yang tertera di atas</li>
                                    <li>Pastikan nominal transfer tepat: <strong>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</strong></li>
                                    <li>Simpan struk/bukti transfer Anda</li>
                                @elseif ($transaction->payment_method === 'midtrans')
                                    <li>Selesaikan pembayaran Anda melalui popup Midtrans yang muncul</li>
                                    <li>Jika popup tidak sengaja tertutup, klik tombol <strong>Bayar Sekarang</strong></li>
                                @elseif ($transaction->payment_method === 'ewallet')
                                    <li>Admin akan menghubungi Anda via WhatsApp untuk memberikan nomor e-wallet tujuan</li>
                                    <li>Lakukan pembayaran sesuai instruksi admin</li>
                                @else
                                    <li>Siapkan uang tunai sebesar <strong>Rp{{ number_format($transaction->total_price, 0, ',', '.') }}</strong></li>
                                    <li>Pembayaran dilakukan saat barang diterima</li>
                                @endif
                            </ol>
                        </div>

                        <!-- Contact Info -->
                        <div class="p-3 bg-info bg-opacity-10 rounded mb-4">
                            <p class="mb-2">
                                <strong>Pertanyaan atau masalah?</strong><br>
                                Hubungi kami di <a href="https://wa.me/6281234567890" target="_blank" class="text-decoration-none">WhatsApp</a> 
                                atau email <a href="mailto:support@kartarumkm.id" class="text-decoration-none">support@kartarumkm.id</a>
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="/katalog" class="btn btn-outline-secondary">
                                <i class="bi bi-shop"></i> Lanjut Belanja
                            </a>
                            <a href="/" class="btn btn-primary">
                                <i class="bi bi-house"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="alert alert-success mt-4" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tip:</strong> Anda akan menerima SMS dan email konfirmasi pesanan. Pastikan nomor telepon dan email Anda benar.
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
