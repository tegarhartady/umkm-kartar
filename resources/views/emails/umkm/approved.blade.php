<x-mail::message>

# Halo {{ $umkm->pemilik }},

Selamat! Pendaftaran UMKM **{{ $umkm->nama_toko }}** telah disetujui oleh tim admin UMKM Kartar Teluknaga.

Sekarang Anda dapat masuk ke dalam dashboard UMKM untuk mengelola produk dan transaksi Anda menggunakan akun berikut:

<div style="background-color: #f0f4ff; padding: 25px; border-radius: 12px; border: 1px dashed #667eea; margin: 20px 0;">
<h3 style="color: #001f5c; margin-top: 0; margin-bottom: 15px;">🔑 Data Login UMKM:</h3>
<table style="width: 100%;">
<tr>
<td style="width: 100px; color: #718096;">Email:</td>
<td style="font-weight: bold; color: #2d3748;">{{ $umkm->email }}</td>
</tr>
<tr>
<td style="color: #718096;">Password:</td>
<td style="font-weight: bold; color: #2d3748; font-family: monospace; font-size: 16px;">{{ $password }}</td>
</tr>
</table>
</div>

<x-mail::button :url="url('/login')" color="primary">
Masuk ke Dashboard
</x-mail::button>

*Demi keamanan, kami sarankan Anda segera mengubah password setelah berhasil masuk pertama kali.*

Terima kasih,<br>
**Tim UMKM Kartar Teluknaga**

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #718096; text-align: center;">
&copy; {{ date('Y') }} Karang Taruna Teluknaga. All rights reserved.
</div>
</x-mail::message>