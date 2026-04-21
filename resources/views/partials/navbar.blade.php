<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); position: fixed; top: 0; width: 100%; z-index: 1000; padding: 10px 0;">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand" href="{{ url('/') }}" style="padding: 0; margin: 0;">
            <img src="/images/logo1.png" alt="Lokalin Logo" style="height: 40px; width: auto;">
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
                    <a class="nav-link {{ Request::is('csr-pik2') ? 'active' : '' }} px-4" href="{{ url('/csr-pik2') }}">DUKUNGAN CSR PIK2</a>
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
