@if(Auth::guard('umkm')->check())
    <!-- UMKM Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('umkm.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-shop me-2"></i>
                <span>{{ Auth::guard('umkm')->user()->nama_toko }}</span>
            </a>
        </div>
        <div class="sidebar-body">
            <nav class="nav flex-column">
                <a href="{{ route('umkm.dashboard') }}" class="nav-link {{ Route::is('umkm.dashboard') ? 'active' : '' }}">
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
@else
    <!-- Admin Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard.admin') }}" class="sidebar-brand">
                <i class="bi bi-speedometer2 me-2"></i>
                <span>Admin Panel</span>
            </a>
        </div>
        <div class="sidebar-body">
            <nav class="nav flex-column">
                <a href="{{ route('dashboard.admin') }}" class="nav-link {{ Route::is('dashboard.admin') ? 'active' : '' }}">
                    <i class="bi bi-house me-2"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="nav-divider my-2"></div>
                <span class="nav-label">UMKM</span>
                <a href="{{ route('admin.umkm.index') }}" class="nav-link {{ Route::is('admin.umkm.*') ? 'active' : '' }}">
                    <i class="bi bi-shop me-2"></i>
                    <span>Daftar UMKM</span>
                </a>
                <a href="{{ route('admin.umkm.verifikasi') }}" class="nav-link {{ Route::is('admin.umkm.verifikasi') ? 'active' : '' }}">
                    <i class="bi bi-check-circle me-2"></i>
                    <span>Verifikasi</span>
                </a>
                
                <div class="nav-divider my-2"></div>
                <span class="nav-label">PRODUK</span>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ Route::is('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box me-2"></i>
                    <span>Produk</span>
                </a>
                <a href="{{ route('admin.products.moderasi') }}" class="nav-link {{ Route::is('admin.products.moderasi') ? 'active' : '' }}">
                    <i class="bi bi-eye me-2"></i>
                    <span>Moderasi</span>
                </a>
                <div class="nav-divider my-2"></div>
                <span class="nav-label">KONTEN</span>
                <a href="{{ route('admin.testimonials.index') }}" class="nav-link {{ Route::is('admin.testimonials.*') ? 'active' : '' }}">
                    <i class="bi bi-chat-left-quote me-2"></i>
                    <span>Testimoni</span>
                </a>
                <a href="{{ route('admin.contact_messages.index') }}" class="nav-link {{ Route::is('admin.contact_messages.*') ? 'active' : '' }}">
                    <i class="bi bi-envelope me-2"></i>
                    <span>Pesan Masuk</span>
                </a>

                <div class="nav-divider my-2"></div>
                <span class="nav-label">LAINNYA</span>
                <a href="{{ route('admin.desa.index') }}" class="nav-link {{ Route::is('admin.desa.*') ? 'active' : '' }}">
                    <i class="bi bi-map me-2"></i>
                    <span>Desa</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people me-2"></i>
                    <span>Manajemen User</span>
                </a>
                <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ Route::is('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-text me-2"></i>
                    <span>Laporan</span>
                </a>
                
                @if(Auth::user() && Auth::user()->role === 'superadmin')
                    <div class="nav-divider my-2"></div>
                    <span class="nav-label">SUPERADMIN</span>
                    <a href="{{ route('superadmin.admins.index') }}" class="nav-link {{ Route::is('superadmin.admins.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge me-2"></i>
                        <span>Kelola Admin</span>
                    </a>
                    <a href="{{ route('superadmin.settings') }}" class="nav-link {{ Route::is('superadmin.settings') ? 'active' : '' }}">
                        <i class="bi bi-gear me-2"></i>
                        <span>Pengaturan</span>
                    </a>
                @endif
            </nav>
        </div>
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" style="width: 100%;">
                @csrf
                <button type="submit" class="nav-link w-100 text-start text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>
@endif