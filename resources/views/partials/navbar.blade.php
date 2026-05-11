<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: fixed; top: 0; width: 100%; z-index: 1000; padding: 10px 0;">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand" href="{{ url('/') }}" style="padding: 0; margin: 0;">
            <img src="{{ App\Models\Setting::get('company_logo') ? asset(App\Models\Setting::get('company_logo')) : asset('images/smartumkm.svg') }}" alt="{{ App\Models\Setting::get('company_name', 'Smart UMKM') }}" style="height: 80px; width: auto;">
        </a>
        

        <!-- Mobile Toggle -->
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }} px-4" href="{{ url('/') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('katalog') ? 'active' : '' }} px-4" href="{{ url('/katalog') }}">Katalog & Desa Mitra</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('csr-pik2') ? 'active' : '' }} px-4" href="{{ url('/csr-pik2') }}">Dukungan CSR PIK2</a>
                </li>
            </ul>

            <!-- Action Buttons (Cart & Login) -->
            <div class="d-flex align-items-center">
                <!-- Cart Icon -->
                @auth
                <a class="nav-link position-relative me-4" href="{{ route('cart.index') }}">
                    <i class="bi bi-cart3 fs-4 text-dark"></i>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; margin-top: 5px;">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>
                @endauth

                @auth
                    <div class="dropdown">
                        <a href="#" class="btn btn-outline-dark px-4 rounded-pill dropdown-toggle shadow-sm" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <li><a class="dropdown-item py-2" href="{{ route('dashboard.admin') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                            @elseif(auth()->guard('umkm')->check() || auth()->user()->role == 'umkm')
                                <li><a class="dropdown-item py-2" href="/umkm/dashboard"><i class="bi bi-shop me-2"></i>Dashboard UMKM</a></li>
                            @endif
                            <li><a class="dropdown-item py-2" href="/profile"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item py-2" href="/orders"><i class="bi bi-bag-check me-2"></i>Riwayat Belanja</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark px-4 rounded-pill shadow-sm">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
