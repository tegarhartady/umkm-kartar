<x-mail::message>

# Halo {{ $umkm->pemilik }},

Password untuk akun UMKM **{{ $umkm->nama_toko }}** telah direset oleh admin UMKM Kartar Teluknaga.

Berikut adalah informasi login terbaru Anda:

<div style="background-color: #fff8f1; padding: 25px; border-radius: 12px; border: 1px dashed #ffa500; margin: 20px 0;">
<h3 style="color: #9c4221; margin-top: 0; margin-bottom: 15px;">🔒 Data Login Terbaru:</h3>
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

*Demi keamanan, kami sangat menyarankan Anda untuk segera mengubah password ini melalui menu profil setelah berhasil masuk.*

Terima kasih,<br>
**Tim UMKM Kartar Teluknaga**

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #718096; text-align: center;">
&copy; {{ date('Y') }} Karang Taruna Teluknaga. All rights reserved.
</div>
</x-mail::message>