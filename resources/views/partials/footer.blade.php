<footer class="footer bg-dark text-white pt-5">
    <div class="container">
        <div class="row gy-4 pb-5">
            <!-- Brand Section -->
            <div class="col-lg-4 col-md-6">
                <a class="d-flex align-items-center mb-4 text-decoration-none" href="{{ url('/') }}">
                    <img src="{{ App\Models\Setting::get('company_logo') ? asset(App\Models\Setting::get('company_logo')) : asset('images/smartumkm.svg') }}" alt="{{ App\Models\Setting::get('company_name', 'Smart UMKM Logo') }}" style="height: 60px; width: auto; margin-right: 12px;">
                    <div>
                        <small class="text-white-50 d-block" style="font-size: 10px; line-height: 1;">{{ App\Models\Setting::get('company_name_short', 'SEDAYU MART') }}</small>
                        <span class="fw-bold text-white" style="font-size: 18px; line-height: 1.2;">UMKM<span class="text-primary">.</span></span>
                    </div>
                </a>
                <p class="text-white-50 mb-4">{{ App\Models\Setting::get('company_description', 'Membangun ekonomi desa Teluknaga melalui digitalisasi UMKM. Kolaborasi antara Karang Taruna dan CSR PIK2.') }}</p>
                <div class="social-links d-flex gap-3">
                    <a href="{{ App\Models\Setting::get('social_facebook', '#') }}" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="{{ App\Models\Setting::get('social_instagram', '#') }}" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="{{ App\Models\Setting::get('social_twitter', '#') }}" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="{{ App\Models\Setting::get('social_youtube', '#') }}" class="social-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-4 text-white">Menu</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/katalog') }}">Katalog & Desa Mitra</a></li>
                    {{-- <li><a href="{{ url('/desa-mitra') }}">Desa Mitra</a></li> --}}
                    <li><a href="{{ url('/csr-pik2') }}">Dukungan CSR PIK2</a></li>
                </ul>
            </div>
            
            <!-- Resources -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-4 text-white">Layanan</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Daftar UMKM</a></li>
                    <li><a href="#">Pelatihan</a></li>
                    <li><a href="#">Pendampingan</a></li>
                    <li><a href="#">Promosi Produk</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-semibold mb-4 text-white">Hubungi Kami</h6>
                <ul class="list-unstyled footer-contact">
                    <li class="d-flex mb-3">
                        <i class="bi bi-geo-alt me-3 text-primary"></i>
                        <span class="text-white-50">{{ App\Models\Setting::get('company_address', 'Desa Teluknaga, Kec. Teluknaga, Kabupaten Tangerang, Banten') }}</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-envelope me-3 text-primary"></i>
                        <span class="text-white-50">{{ App\Models\Setting::get('company_email', 'info@kartarteluknaga.id') }}</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-telephone me-3 text-primary"></i>
                        <span class="text-white-50">{{ App\Models\Setting::get('company_phone', '+62 812 3456 7890') }}</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-top border-secondary py-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} Sedayu Mart UMKM. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-white-50 mb-0 small">Kolaborasi dengan CSR PIK2</p>
                </div>
            </div>
        </div>
    </div>
</footer>
