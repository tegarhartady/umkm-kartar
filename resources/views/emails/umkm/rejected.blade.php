<x-mail::message>
# Halo {{ $umkm->pemilik }},

Terima kasih telah mendaftarkan UMKM **{{ $umkm->nama_toko }}** di platform kami.

Kami mohon maaf, setelah melakukan peninjauan terhadap berkas dan informasi yang Anda berikan, pendaftaran UMKM Anda **belum dapat kami setujui** saat ini dengan alasan berikut:

<div style="background-color: #fff5f5; padding: 20px; border-radius: 10px; border-left: 4px solid #f56565; margin: 20px 0; color: #c53030;">
<strong>Alasan Penolakan:</strong><br>
{{ $reason }}
</div>

**Alasan Umum Penolakan Lainnya (jika ada):**
* Data KTP tidak terbaca atau tidak sesuai.
* Foto tempat usaha tidak jelas.
* Informasi kategori atau deskripsi produk kurang lengkap.

Jika Anda merasa ada kesalahan atau ingin melengkapi data Anda, silakan hubungi tim admin kami atau ajukan pendaftaran ulang dengan data yang lebih lengkap.

Terima kasih atas pengertian Anda.

Salam,<br>
**Tim UMKM Kartar Teluknaga**

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #718096; text-align: center;">
&copy; {{ date('Y') }} Karang Taruna Teluknaga. All rights reserved.
</div>
</x-mail::message>
