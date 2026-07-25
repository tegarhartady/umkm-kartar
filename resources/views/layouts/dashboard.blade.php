<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard - Karang Taruna Teluknaga')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Custom CSS Variables -->
    <style>
        :root {
            --primary-color: #667eea;
            --primary-dark: #764ba2;
            --primary-light: #f3f0ff;
            --secondary-color: #ffd700;
            --accent-color: #ffa500;
            --text-dark: #2d3748;
            --text-muted: #718096;
            --bg-light: #f7fafc;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.12);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.15);
            --shadow-xl: 0 20px 25px rgba(0, 0, 0, 0.15);
        }

        .sidebar {
            background: #001f5c;
        }

        .sidebar .nav-link {
            color: #000f3d !important;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #000f3d !important;
        }

        .sidebar .nav-link.active {
            background: #000f3d;
            border-left: 3px solid 000f3d;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 31, 92, 0.1);
        }

        .navbar-brand {
            color: #001f5c !important;
            font-weight: 700;
        }

        .nav-link {
            color: #333 !important;
        }

        .nav-link:hover {
            color: #001f5c !important;
        }

        .nav-link.active {
            color: #ffffff !important;
            border-bottom: 2px solid #001f5c;
        }

        .card {
            border: 1px solid #e0e0e0;
            border-radius: 12px;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
        }

        .badge-primary {
            background: #001f5c;
        }

        .badge-success {
            background: #28a745;
        }

        .badge-warning {
            background: #ffc107;
            color: #333;
        }

        .badge-danger {
            background: #dc3545;
        }
    </style>

    @stack('styles')
</head>

<body class="dashboard-layout">

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="brand-logo">
                <img src="{{ asset('images/smartumkm.svg') }}" alt="Smart UMKM Logo" style="height: 60px; width: auto;">
                <div class="brand-text">
                    <span class="brand-name">SMARTUMKM</span>
                    <small class="brand-subtitle">Dashboard</small>
                </div>
            </div>
            <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="sidebar-content">
            <!-- User Info -->
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-details">
                    <h6 class="user-name">{{ auth()->user()->name }}</h6>
                    <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <span class="nav-section-title">MENU UTAMA</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('dashboard.admin') }}" class="nav-link {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.umkm.index') }}" class="nav-link {{ request()->routeIs('admin.umkm.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-shop"></i>
                                <span class="nav-text">Kelola UMKM</span>
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </a>
                            <ul class="nav-submenu {{ request()->routeIs('admin.umkm.*') ? 'show' : '' }}">
                                <li><a href="{{ route('admin.umkm.index') }}">Daftar UMKM</a></li>
                                <li><a href="{{ route('admin.umkm.verifikasi') }}">Verifikasi</a></li>
                                <li><a href="{{ route('admin.umkm.create') }}">Tambah UMKM</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-box-seam"></i>
                                <span class="nav-text">Produk</span>
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </a>
                            <ul class="nav-submenu {{ request()->routeIs('admin.products.*') ? 'show' : '' }}">
                                <li><a href="{{ route('admin.products.index') }}">Semua Produk</a></li>
                                <li><a href="{{ route('admin.products.moderasi') }}">Moderasi</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-graph-up"></i>
                                <span class="nav-text">Laporan</span>
                                <i class="bi bi-chevron-down ms-auto nav-arrow"></i>
                            </a>
                            <ul class="nav-submenu {{ request()->routeIs('admin.laporan.*') ? 'show' : '' }}">
                                <li><a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.index') ? 'active' : '' }}">Laporan Umum</a></li>
                                <li><a href="{{ route('admin.laporan.transaksi_desa') }}" class="{{ request()->routeIs('admin.laporan.transaksi_desa') ? 'active' : '' }}">Transaksi per Desa</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-receipt"></i>
                                <span class="nav-text">Transaksi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.desa.index') }}" class="nav-link {{ request()->routeIs('admin.desa.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-geo-alt"></i>
                                <span class="nav-text">Kelola Desa</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <span class="nav-text">Manajemen User</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">KONTEN</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-chat-left-quote"></i>
                                <span class="nav-text">Testimoni</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.promotions.index') }}" class="nav-link {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-megaphone"></i>
                                <span class="nav-text">Promo & Event</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.contact_messages.index') }}" class="nav-link {{ request()->routeIs('admin.contact_messages.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-envelope"></i>
                                <span class="nav-text">Pesan Masuk</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">MASTER DATA</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('admin.master.categories.index') }}" class="nav-link {{ request()->routeIs('admin.master.categories.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-grid"></i>
                                <span class="nav-text">Master Kategori</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.master.units.index') }}" class="nav-link {{ request()->routeIs('admin.master.units.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-list-ul"></i>
                                <span class="nav-text">Master Satuan</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.master.banks.index') }}" class="nav-link {{ request()->routeIs('admin.master.banks.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-bank"></i>
                                <span class="nav-text">Master Bank</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.payment') }}" class="nav-link {{ request()->route('admin.settings.payment') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-wallet2"></i>
                                <span class="nav-text">Master Pembayaran</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.delivery') }}" class="nav-link {{ request()->route('admin.settings.delivery') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-truck"></i>
                                <span class="nav-text">Master Pengiriman</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="nav-section">
                    <span class="nav-section-title">LAINNYA</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('admin.settings.company') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-gear"></i>
                                <span class="nav-text">Pengaturan Umum</span>
                            </a>
                        </li>
                    </ul>
                </div>

                @if(auth()->user()->role === 'superadmin')
                <div class="nav-section">
                    <span class="nav-section-title">SUPER ADMIN</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ route('superadmin.admins.index') }}" class="nav-link {{ request()->routeIs('superadmin.admins.*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-people"></i>
                                <span class="nav-text">Kelola Admin</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('superadmin.settings') }}" class="nav-link {{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-gear"></i>
                                <span class="nav-text">Pengaturan</span>
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                <div class="nav-section">
                    <span class="nav-section-title">LAINNYA</span>
                    <ul class="nav-list">
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link" target="_blank">
                                <i class="nav-icon bi bi-house"></i>
                                <span class="nav-text">Website Utama</span>
                                <i class="nav-external bi bi-box-arrow-up-right"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}" class="nav-form">
                                @csrf
                                <button type="submit" class="nav-link logout-btn">
                                    <i class="nav-icon bi bi-box-arrow-right"></i>
                                    <span class="nav-text">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-left">
                <button class="sidebar-toggle d-lg-none" id="sidebarToggleMain">
                    <i class="bi bi-list"></i>
                </button>
                <div class="page-breadcrumb">
                    @yield('breadcrumb')
                </div>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="header-btn" data-bs-toggle="dropdown">
                            <i class="bi bi-bell"></i>
                            <span class="badge">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <h6 class="dropdown-header">Notifikasi</h6>
                            <a class="dropdown-item" href="#">
                                <div class="notification-item">
                                    <i class="bi bi-shop text-primary"></i>
                                    <div>
                                        <span>UMKM baru mendaftar</span>
                                        <small>2 menit yang lalu</small>
                                    </div>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="#">Lihat Semua</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="page-content">
            @yield('content')
        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        // Initialize AOS
        AOS.init();

        // Sidebar Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggleMain = document.getElementById('sidebarToggleMain');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            // Toggle sidebar
            function toggleSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            }

            // Event listeners
            if (sidebarToggleMain) {
                sidebarToggleMain.addEventListener('click', toggleSidebar);
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Submenu toggle
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link.nextElementSibling && link.nextElementSibling.classList.contains('nav-submenu')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const submenu = this.nextElementSibling;
                        const arrow = this.querySelector('.nav-arrow');

                        submenu.classList.toggle('show');
                        arrow.classList.toggle('rotated');
                    });
                }
            });
        });
    </script>

    @stack('scripts')

    <style>
        /* Dashboard Layout Styles */
        .dashboard-layout {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            background: white;
            border-right: 1px solid #e9ecef;
            z-index: 1050;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        @media (min-width: 992px) {
            .sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 280px;
            }
        }

        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #f1f3f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo img {
            height: 60px;
            width: auto;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .brand-name {
            font-weight: 700;
            font-size: 16px;
            color: var(--text-dark);
            line-height: 1;
        }

        .brand-subtitle {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-muted);
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background: #f1f3f5;
            color: var(--text-dark);
        }

        .sidebar-content {
            padding: 0 24px 24px;
        }

        /* User Info */
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 24px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 20px;
            border: 2px solid var(--primary-color);
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .user-role {
            font-size: 12px;
            color: var(--text-muted);
            background: white;
            padding: 2px 8px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Navigation */
        .nav-section {
            margin-bottom: 32px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
            display: block;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.2s;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .nav-link:hover {
            background: #f8f9fa;
            color: var(--text-dark);
        }

        .nav-link.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
            padding-left: 13px;
        }

        .nav-icon {
            width: 20px;
            font-size: 16px;
            margin-right: 12px;
        }

        .nav-text {
            flex: 1;
        }

        .nav-arrow {
            font-size: 12px;
            transition: transform 0.2s;
        }

        .nav-arrow.rotated {
            transform: rotate(90deg);
        }

        .nav-external {
            font-size: 12px;
            opacity: 0.5;
        }

        .nav-submenu {
            list-style: none;
            padding: 0;
            margin: 8px 0 0 48px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .nav-submenu.show {
            max-height: 200px;
        }

        .nav-submenu li {
            margin-bottom: 4px;
        }

        .nav-submenu a {
            display: block;
            padding: 8px 12px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.2s;
        }

        .nav-submenu a:hover {
            background: #f1f3f5;
            color: var(--text-dark);
        }

        .nav-form {
            margin: 0;
        }

        .logout-btn {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
        }

        /* Main Content */
        .main-content {
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .top-header {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1040;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .page-breadcrumb {
            font-size: 14px;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-btn {
            background: none;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: all 0.2s;
            position: relative;
        }

        .header-btn:hover {
            background: #f1f3f5;
            color: var(--text-dark);
        }

        .header-btn .badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #dc3545;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-content {
            padding: 24px;
        }

        /* Notification Dropdown */
        .notification-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 0;
        }

        .notification-item i {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        .notification-item div {
            flex: 1;
        }

        .notification-item span {
            display: block;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .notification-item small {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1049;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .top-header {
                padding: 12px 16px;
            }

            .page-content {
                padding: 16px;
            }
        }
    </style>
</body>

</html>