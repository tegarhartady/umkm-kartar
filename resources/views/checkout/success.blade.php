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
                            <strong>Kode Checkout:</strong>
                            <div class="mt-2">
                                <code style="font-size: 1.2rem; background: #f8f9fa; padding: 10px; border-radius: 5px; display: inline-block;">
                                    {{ $transaction->checkout_code ?? $transaction->transaction_code }}
                                </code>
                            </div>
                            <small class="text-muted d-block mt-2">Gunakan kode ini untuk melacak seluruh pesanan Anda</small>
                        </div>

                        <!-- Order Details -->
                        <div class="mb-4">
                            <div class="card border-0 bg-light rounded-4 overflow-hidden shadow-sm">
                                <div class="card-header bg-white border-0 py-3 text-start">
                                    <h6 class="mb-0 fw-bold"><i class="bi bi-box-seam me-2 text-success"></i>Rincian Seluruh Produk</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-borderless align-middle mb-0 text-start">
                                            <tbody>
                                                @php 
                                                    $totalShipping = 0;
                                                    $totalAll = 0;
                                                @endphp
                                                @foreach($transactions as $t)
                                                    @php 
                                                        $totalShipping += $t->delivery_fee;
                                                        $totalAll += $t->total_price;
                                                    @endphp
                                                    @forelse($t->items as $item)
                                                    <tr>
                                                        <td style="width: 70px; padding-left: 1.5rem;">
                                                            <img src="{{ ($item->product && $item->product->image) ? asset('storage/' . $item->product->image) : asset('images/no-image.png') }}" 
                                                                 class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold">{{ $item->product_name }}</div>
                                                            <small class="text-muted">{{ $item->quantity }} x Rp{{ number_format($item->price, 0, ',', '.') }}</small>
                                                            <div class="text-primary" style="font-size: 0.7rem;">Penjual: {{ $t->umkm->nama_toko ?? 'UMKM' }}</div>
                                                        </td>
                                                        <td class="text-end fw-bold" style="padding-right: 1.5rem;">
                                                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @empty
                                                        @if($t->product)
                                                        <tr>
                                                            <td style="width: 70px; padding-left: 1.5rem;">
                                                                <img src="{{ $t->product->image ? asset('storage/' . $t->product->image) : asset('images/no-image.png') }}" 
                                                                     class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                        </td>
                                                        <td>
                                                            <div class="fw-bold">{{ $t->product->nama_produk }}</div>
                                                            <small class="text-muted">{{ $t->quantity }} x Rp{{ number_format($t->price, 0, ',', '.') }}</small>
                                                            <div class="text-primary" style="font-size: 0.7rem;">Penjual: {{ $t->umkm->nama_toko ?? 'UMKM' }}</div>
                                                        </td>
                                                        <td class="text-end fw-bold" style="padding-right: 1.5rem;">
                                                            Rp{{ number_format($t->price * $t->quantity, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforelse
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-white border-top">
                                                <tr>
                                                    <td colspan="2" class="text-end text-muted small py-2" style="padding-left: 1.5rem;">Total Biaya Kirim ({{ count($transactions) }} UMKM)</td>
                                                    <td class="text-end fw-bold py-2" style="padding-right: 1.5rem;">Rp{{ number_format($totalShipping, 0, ',', '.') }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" class="text-end fw-bold py-3" style="padding-left: 1.5rem;">Total Tagihan Keseluruhan</td>
                                                    <td class="text-end fw-bold py-3 text-success fs-5" style="padding-right: 1.5rem;">Rp{{ number_format($totalAll, 0, ',', '.') }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded shadow-sm h-100">
                                    <small class="text-muted d-block mb-1 text-start">Jenis & Distribusi</small>
                                    <p class="fw-bold mb-0 text-start">
                                        {{ $transaction->order_type === 'po' ? 'Pre-Order' : 'Langsung Kirim' }} 
                                        <span class="text-muted mx-1">|</span>
                                        {{ $transaction->delivery_type === 'delivery' ? 'Diantar' : 'Take Away' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded shadow-sm h-100">
                                    <small class="text-muted d-block mb-1 text-start">Metode Pembayaran</small>
                                    <p class="fw-bold mb-0 text-uppercase text-start">
                                        @php
                                            $methods = [
                                                'transfer' => 'Transfer Bank',
                                                'qris' => 'QRIS',
                                                'ewallet' => 'E-Wallet',
                                                'cod' => 'COD',
                                                'midtrans' => 'Midtrans'
                                            ];
                                        @endphp
                                        {{ $methods[$transaction->payment_method] ?? $transaction->payment_method }}
                                    </p>
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
                            $isProduction = (App\Models\Setting::get('midtrans_is_production', '0')) == '1';
                            $snapUrl = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
                            $clientKey = App\Models\Setting::get('midtrans_client_key', config('midtrans.client_key'));
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
                        @if($transaction->payment_method === 'qris' || $transaction->payment_method === 'qris_event')
                        <div class="card border-primary mb-4 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-qr-code-scan me-2"></i>Scan QRIS untuk Pembayaran</h6>
                                
                                @if($transaction->payment_method === 'qris_event')
                                    <div class="row justify-content-center g-3">
                                        @foreach($transactions as $t)
                                            <div class="col-md-6 mb-3">
                                                <div class="p-3 bg-light rounded border h-100">
                                                    <h6 class="fw-bold mb-2">QRIS UMKM: {{ $t->umkm->nama_toko ?? 'UMKM' }}</h6>
                                                    @if($t->umkm && $t->umkm->foto_qris)
                                                        <img src="{{ asset('storage/' . $t->umkm->foto_qris) }}" class="img-fluid rounded mb-2 shadow-sm" style="max-height: 250px; border: 1px solid #eee;">
                                                        <p class="small text-muted mb-0">Bayar: <strong>Rp{{ number_format($t->total_price, 0, ',', '.') }}</strong></p>
                                                    @else
                                                        <div class="alert alert-warning py-2 mb-0 small">QRIS UMKM ini belum tersedia. Silakan hubungi UMKM langsung atau gunakan metode lain.</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="alert alert-info py-2 mt-3 mb-0 small">
                                        <i class="bi bi-info-circle me-1"></i> Jika Anda memesan dari beberapa UMKM, pastikan untuk men-scan masing-masing QRIS dan unggah seluruh bukti pembayarannya di bawah (bisa dijadikan 1 foto kolase jika lebih dari 1).
                                    </div>
                                @else
                                    @php
                                        $qrisImage = App\Models\Setting::get('payment_qris_image');
                                    @endphp
                                    @if($qrisImage)
                                        <img src="{{ asset($qrisImage) }}" class="img-fluid rounded mb-3 shadow-sm" style="max-width: 300px; border: 1px solid #eee;">
                                        <div class="alert alert-info py-2 mb-0 small">
                                            <i class="bi bi-info-circle me-1"></i> Setelah scan dan bayar, mohon simpan bukti pembayaran Anda.
                                        </div>
                                    @else
                                        <div class="alert alert-warning">Gambar QRIS belum tersedia. Silakan hubungi admin.</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Bank Info for Transfer -->
                        @if($transaction->payment_method === 'transfer')
                        <div class="card border-info mb-4 shadow-sm">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3 text-info"><i class="bi bi-bank me-2"></i>Informasi Rekening Transfer</h6>
                                <p class="text-muted small mb-3 text-start">Pilih bank tujuan transfer Anda:</p>
                                
                                <div class="mb-3 text-start">
                                    <select id="bank-selector" class="form-select border-info">
                                        <option value="" selected disabled>-- Pilih Nama Bank --</option>
                                        @forelse($banks ?? [] as $bank)
                                            <option value="{{ $bank->id }}" 
                                                    data-number="{{ $bank->account_number }}" 
                                                    data-holder="{{ $bank->account_holder }}"
                                                    data-name="{{ $bank->bank_name }}">
                                                Bank {{ $bank->bank_name }}
                                            </option>
                                        @empty
                                            @php
                                                $bankName = App\Models\Setting::get('payment_bank_name', '-');
                                                $bankAcc = App\Models\Setting::get('payment_bank_account', '-');
                                                $bankHolder = App\Models\Setting::get('payment_bank_holder', '-');
                                            @endphp
                                            <option value="default" 
                                                    data-number="{{ $bankAcc }}" 
                                                    data-holder="{{ $bankHolder }}"
                                                    data-name="{{ $bankName }}">
                                                Bank {{ $bankName }}
                                            </option>
                                        @endforelse
                                    </select>
                                </div>

                                <div id="bank-detail-container" class="d-none">
                                    <div class="p-3 bg-info bg-opacity-10 rounded border border-info border-opacity-25 text-start">
                                        <div class="row align-items-center">
                                            <div class="col-8">
                                                <p class="mb-1 text-muted small fw-bold" id="display-bank-name"></p>
                                                <p class="mb-1 h4 fw-bold text-dark" id="display-bank-number"></p>
                                                <p class="mb-0 text-muted small" id="display-bank-holder"></p>
                                            </div>
                                            <div class="col-4 text-end">
                                                <button class="btn btn-sm btn-info text-white" onclick="copySelectedBank()">
                                                    <i class="bi bi-clipboard me-1"></i>Salin
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            const bankSelector = document.getElementById('bank-selector');
                            const detailContainer = document.getElementById('bank-detail-container');
                            const displayBankName = document.getElementById('display-bank-name');
                            const displayBankNumber = document.getElementById('display-bank-number');
                            const displayBankHolder = document.getElementById('display-bank-holder');

                            bankSelector.addEventListener('change', function() {
                                const selectedOption = this.options[this.selectedIndex];
                                if (selectedOption.value) {
                                    displayBankName.textContent = 'Bank ' + selectedOption.getAttribute('data-name');
                                    displayBankNumber.textContent = selectedOption.getAttribute('data-number');
                                    displayBankHolder.textContent = 'a.n ' + selectedOption.getAttribute('data-holder');
                                    detailContainer.classList.remove('d-none');
                                } else {
                                    detailContainer.classList.add('d-none');
                                }
                            });

                            function copySelectedBank() {
                                const number = displayBankNumber.textContent;
                                if (number) {
                                    navigator.clipboard.writeText(number).then(() => {
                                        const toast = document.createElement('div');
                                        toast.className = 'position-fixed bottom-0 start-50 translate-middle-x mb-4 p-3 bg-dark text-white rounded shadow-lg';
                                        toast.style.zIndex = '9999';
                                        toast.innerHTML = '<i class="bi bi-check-circle-fill text-success me-2"></i> Nomor rekening berhasil disalin!';
                                        document.body.appendChild(toast);
                                        setTimeout(() => toast.remove(), 2000);
                                    });
                                }
                            }
                        </script>
                        @endif

                        <!-- Upload Payment Proof -->
                        @if(in_array($transaction->payment_method, ['transfer', 'qris', 'qris_event']) && $transaction->status === 'pending')
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
                                @if ($transaction->payment_method === 'qris' || $transaction->payment_method === 'qris_event')
                                    <li>Buka aplikasi e-wallet atau mobile banking Anda (Gopay, OVO, Dana, dll)</li>
                                    <li>Gunakan fitur <strong>Scan/Bayar</strong> dan arahkan ke kode QR di atas @if($transaction->payment_method === 'qris_event') (Scan masing-masing QRIS UMKM) @endif</li>
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
                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                            <a href="/katalog" class="btn btn-outline-secondary">
                                <i class="bi bi-shop me-1"></i> Lanjut Belanja
                            </a>
                            <a href="/" class="btn btn-primary">
                                <i class="bi bi-house me-1"></i> Kembali ke Beranda
                            </a>
                            @if($transaction->status === 'pending')
                                <form action="{{ route('transaction.cancel', $transaction->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-circle me-1"></i> Batal Pembelian
                                    </button>
                                </form>
                            @endif
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
