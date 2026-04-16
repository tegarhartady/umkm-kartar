<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm py-3">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <div class="brand-icon me-2">
                <span>TN</span>
            </div>
            <div class="brand-text">
                <small class="text-muted d-block" style="font-size: 10px; line-height: 1;">KARANG TARUNA</small>
                <span class="fw-bold text-dark" style="font-size: 16px; line-height: 1.2;">TELUKNAGA<span class="text-primary">.</span></span>
            </div>
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
                    <a class="nav-link {{ Request::is('katalog') ? 'active' : '' }} px-4" href="{{ url('/katalog') }}">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('desa-mitra') ? 'active' : '' }} px-4" href="{{ url('/desa-mitra') }}">Desa Mitra</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('csr-pik2') ? 'active' : '' }} px-4" href="{{ url('/csr-pik2') }}">CSR PIK2</a>
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
                            @else
                                <li><a class="dropdown-item" href="{{ route('dashboard.umkm') }}">Dashboard UMKM</a></li>
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
                    <a href="{{ url('/login') }}" class="btn btn-dark px-4 rounded-pill">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
