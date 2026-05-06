<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sedayu Mart UMKM">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sedayu Mart UMKM')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @stack('styles')

    <style>
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
            color: #001f5c !important;
            border-bottom: 2px solid #001f5c;
        }

        .sidebar {
            background: #001f5c;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .sidebar .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            background: #000f3d;
            color: white !important;
            border-left: 3px solid white;
        }

        .section-badge {
            background: #e8eef7 !important;
            color: #001f5c !important;
            border-color: #001f5c !important;
        }

        .text-primary {
            color: #001f5c !important;
        }

        .bg-primary {
            background-color: #001f5c !important;
        }

        .border-primary {
            border-color: #001f5c !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #001f5c 0%, #000f3d 100%);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #000f3d 0%, #001f5c 100%);
            color: white;
        }

        .btn-outline-primary {
            color: #001f5c;
            border-color: #001f5c;
        }

        .btn-outline-primary:hover {
            background: #001f5c;
            border-color: #001f5c;
            color: white;
        }

        .feature-icon {
            color: #001f5c;
        }

        .contact-icon {
            color: #001f5c;
        }

        .stat-number {
            color: #001f5c;
        }

        html, body {
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    @include('partials.navbar')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/main.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
