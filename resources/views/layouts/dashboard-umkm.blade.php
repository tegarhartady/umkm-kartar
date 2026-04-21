<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard UMKM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
</head>
<body>
<div class="dashboard-wrapper">
    <div class="wrapper">
        <!-- UMKM Sidebar -->
        <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('umkm.umkm.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-shop me-2"></i>
                <span>{{ Auth::guard('umkm')->user()->nama_toko }}</span>
            </a>
        </div>
        <div class="sidebar-body">
            <nav class="nav flex-column">
                <a href="{{ route('umkm.umkm.dashboard') }}" class="nav-link {{ Route::is('umkm.umkm.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house me-2"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="nav-divider my-2"></div>
                <span class="nav-label">PRODUK</span>
                
                <a href="{{ route('umkm.products.index') }}" class="nav-link {{ Route::is('umkm.products.index') ? 'active' : '' }}">
                    <i class="bi bi-box me-2"></i>
                    <span>Daftar Produk</span>
                </a>
                <a href="{{ route('umkm.products.create') }}" class="nav-link {{ Route::is('umkm.products.create') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle me-2"></i>
                    <span>Tambah Produk</span>
                </a>
                
                @if(request()->route('product'))
                    <a href="{{ route('umkm.products.edit', request()->route('product')->id) }}" class="nav-link {{ Route::is('umkm.products.edit') ? 'active' : '' }}">
                        <i class="bi bi-pencil me-2"></i>
                        <span>Edit Produk</span>
                    </a>
                @endif
            </nav>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('umkm.logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="nav-link w-100 text-start text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    @yield('breadcrumb')
                </div>
                <div class="topbar-right">
                    <button class="btn btn-sm btn-light" id="toggleSidebar">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        background: #f8f9fa;
    }

    .dashboard-wrapper {
        display: flex;
        min-height: 100vh;
    }
    
    .wrapper {
        display: flex;
        width: 100%;
    }
    
    .sidebar {
        width: 250px;
        background: #fff;
        border-right: 1px solid #e9ecef;
        display: flex;
        flex-direction: column;
        position: fixed;
        height: 100vh;
        left: 0;
        top: 0;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 2px 0 4px rgba(0,0,0,0.05);
    }
    
    .main-content {
        flex: 1;
        margin-left: 250px;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    .topbar {
        background: #fff;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .page-content {
        flex: 1;
        padding: 2rem 1.5rem;
        background: #f8f9fa;
    }
    
    .sidebar-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .sidebar-brand {
        display: flex;
        align-items: center;
        font-weight: 700;
        color: #333;
        text-decoration: none;
        font-size: 1rem;
    }
    
    .sidebar-body {
        flex: 1;
        padding: 1rem 0;
        overflow-y: auto;
    }
    
    .nav {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: #6c757d;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        font-size: 0.95rem;
    }
    
    .nav-link:hover {
        background: #f8f9fa;
        color: #667eea;
        border-left-color: #667eea;
    }
    
    .nav-link.active {
        background: #f8f9fa;
        color: #667eea;
        border-left-color: #667eea;
        font-weight: 600;
    }
    
    .nav-divider {
        height: 1px;
        background: #e9ecef;
        margin: 0.75rem 0;
    }
    
    .nav-label {
        display: block;
        padding: 0.75rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #adb5bd;
        text-transform: uppercase;
    }
    
    .sidebar-footer {
        padding: 1rem;
        border-top: 1px solid #e9ecef;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .sidebar.show {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleSidebarBtn = document.getElementById('toggleSidebar');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleSidebarBtn) {
        toggleSidebarBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }
</script>
</body>
</html>