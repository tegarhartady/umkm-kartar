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
            
            <!-- Login Button -->
            <div class="d-flex">
                @auth
                    <div class="dropdown">
                        <a href="#" class="btn btn-outline-dark px-4 rounded-pill dropdown-toggle" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            @if(in_array(auth()->user()->role, ['admin', 'superadmin']))
                                <li><a class="dropdown-item" href="{{ route('dashboard.admin') }}">Dashboard Admin</a></li>
                            @elseif(auth()->guard('umkm')->check())
                                <li><a class="dropdown-item" href="/umkm/dashboard">Dashboard UMKM</a></li>
                            @endif
                            <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profil Saya</a></li>
                            <li><a class="dropdown-item" href="/orders"><i class="bi bi-bag-check me-2"></i>Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-dark px-4 rounded-pill">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
