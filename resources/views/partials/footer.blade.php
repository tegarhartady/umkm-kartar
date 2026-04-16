<footer class="footer bg-dark text-white pt-5">
    <div class="container">
        <div class="row gy-4 pb-5">
            <!-- Brand Section -->
            <div class="col-lg-4 col-md-6">
                <a class="d-flex align-items-center mb-4 text-decoration-none" href="{{ url('/') }}">
                    <div class="brand-icon-footer me-2">
                        <span>TN</span>
                    </div>
                    <div>
                        <small class="text-white-50 d-block" style="font-size: 10px; line-height: 1;">KARANG TARUNA</small>
                        <span class="fw-bold text-white" style="font-size: 18px; line-height: 1.2;">TELUKNAGA<span class="text-primary">.</span></span>
                    </div>
                </a>
                <p class="text-white-50 mb-4">Membangun ekonomi desa Teluknaga melalui digitalisasi UMKM. Kolaborasi antara Karang Taruna dan CSR PIK2.</p>
                <div class="social-links d-flex gap-3">
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-4">Menu</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="{{ url('/katalog') }}">Katalog</a></li>
                    <li><a href="{{ url('/desa-mitra') }}">Desa Mitra</a></li>
                    <li><a href="{{ url('/csr-pik2') }}">CSR PIK2</a></li>
                </ul>
            </div>
            
            <!-- Resources -->
            <div class="col-lg-2 col-md-6">
                <h6 class="fw-semibold mb-4">Layanan</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="#">Daftar UMKM</a></li>
                    <li><a href="#">Pelatihan</a></li>
                    <li><a href="#">Pendampingan</a></li>
                    <li><a href="#">Promosi Produk</a></li>
                </ul>
            </div>
            
            <!-- Contact -->
            <div class="col-lg-4 col-md-6">
                <h6 class="fw-semibold mb-4">Hubungi Kami</h6>
                <ul class="list-unstyled footer-contact">
                    <li class="d-flex mb-3">
                        <i class="bi bi-geo-alt me-3 text-primary"></i>
                        <span class="text-white-50">Desa Teluknaga, Kec. Teluknaga, Kabupaten Tangerang, Banten</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-envelope me-3 text-primary"></i>
                        <span class="text-white-50">info@kartarteluknaga.id</span>
                    </li>
                    <li class="d-flex mb-3">
                        <i class="bi bi-telephone me-3 text-primary"></i>
                        <span class="text-white-50">+62 812 3456 7890</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-top border-secondary py-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} Karang Taruna Teluknaga. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="text-white-50 mb-0 small">Kolaborasi dengan CSR PIK2</p>
                </div>
            </div>
        </div>
    </div>
</footer>
