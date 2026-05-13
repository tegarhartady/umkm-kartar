<x-mail::message>
# Halo {{ $umkm->pemilik }},

Terima kasih telah mendaftarkan UMKM **{{ $umkm->nama_toko }}** di platform UMKM Kartar Teluknaga.

Pendaftaran Anda telah kami terima dan saat ini sedang dalam proses peninjauan oleh tim admin kami.

<div style="background-color: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 4px solid #001f5c; margin: 20px 0;">
<h3 style="margin-top: 0; color: #001f5c; font-size: 16px;">Detail Pendaftaran:</h3>
<table style="width: 100%; border-collapse: collapse;">
<tr>
<td style="padding: 5px 0; color: #718096; width: 120px;">Nama Toko:</td>
<td style="padding: 5px 0; font-weight: bold;">{{ $umkm->nama_toko }}</td>
</tr>
<tr>
<td style="padding: 5px 0; color: #718096;">Kategori:</td>
<td style="padding: 5px 0; font-weight: bold;">{{ $umkm->kategori }}</td>
</tr>
<tr>
<td style="padding: 5px 0; color: #718096;">Status:</td>
<td style="padding: 5px 0;"><span style="color: #ffa500; font-weight: bold;">Menunggu Persetujuan</span></td>
</tr>
</table>
</div>

Mohon tunggu informasi selanjutnya. Kami akan mengirimkan email konfirmasi segera setelah pendaftaran Anda disetujui.

Terima kasih,<br>
**Tim UMKM Kartar Teluknaga**

<div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #718096; text-align: center;">
&copy; {{ date('Y') }} Karang Taruna Teluknaga. All rights reserved.
</div>
</x-mail::message>
