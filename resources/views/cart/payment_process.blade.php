@extends('layouts.app')

@section('title', 'Proses Pembayaran - Karang Taruna Teluknaga')

@section('content')
<section class="py-5 mt-5">
    <div class="container text-center py-5">
        <div class="spinner-border text-success mb-4" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h3 class="fw-bold">Menghubungkan ke Gateway Pembayaran...</h3>
        <p class="text-muted">Mohon tunggu sebentar, jangan tutup halaman ini.</p>
    </div>
</section>

@push('scripts')
@php
    $settings = \App\Models\Setting::whereIn('key', ['midtrans_is_production', 'midtrans_client_key'])->get()->keyBy('key');
    $isProduction = ($settings['midtrans_is_production']?->value ?? '0') == '1';
    $snapSrc = $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js';
    $clientKey = $settings['midtrans_client_key']?->value ?? '';
@endphp
<script src="{{ $snapSrc }}" data-client-key="{{ $clientKey }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.snap !== 'undefined') {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ route('checkout.success', $transactions[0]->id) }}";
                },
                onPending: function(result) {
                    window.location.href = "{{ route('checkout.success', $transactions[0]->id) }}";
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                    window.location.href = "{{ route('orders.index') }}";
                },
                onClose: function() {
                    window.location.href = "{{ route('checkout.success', $transactions[0]->id) }}";
                }
            });
        } else {
            alert('Midtrans Snap tidak termuat. Silakan coba lagi.');
            window.location.href = "{{ route('orders.index') }}";
        }
    });
</script>
@endpush
@endsection
