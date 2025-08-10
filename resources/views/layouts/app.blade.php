<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PLN Monitoring Aset') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS untuk sidebar saja -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary: #0066cc;
            --secondary: #ff6600;
            --dark: #1a1a2e;
            --light: #f5f5f5;
        }
        .sidebar-link.active {
            background-color: rgba(0, 102, 204, 0.1);
            color: var(--primary);
            font-weight: bold;
            border-left: 4px solid var(--primary);
        }
        /* Pastikan konten utama menggunakan font Bootstrap */
        .bootstrap-content {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        /* Efek hover untuk dropdown */
        .hover-bg-gray-100:hover {
            background-color: #f8f9fa !important;
        }
        /* Judul dashboard di navbar */
        .dashboard-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a1a2e;
        }
    </style>
</head>
<body class="font-sans bg-gray-50 flex h-screen">

    {{-- Sidebar dengan Tailwind --}}
    <aside class="w-64 bg-white shadow-lg flex flex-col justify-between py-6">
        <div>
            <div class="px-6 mb-8 text-center">
                <img src="{{ asset('assets/images/foto.png') }}" alt="Logo PLN" class="h-12 mx-auto mb-2">
                <span class="text-xl font-bold text-blue-800 block">Asset Monitoring</span>
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }} flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-r-full">
                            <i class="fas fa-tachometer-alt mr-3 text-lg"></i> Dashboard
                        </a>
                    </li>
                    <li class="mt-2">
                        <a href="{{ route('aset.index') }}" class="sidebar-link {{ request()->is('aset*') ? 'active' : '' }} flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-r-full">
                            <i class="fas fa-boxes mr-3 text-lg"></i> Kelola Aset
                        </a>
                    </li>
                    <li class="mt-2">
                        <a href="{{ route('bidang.index') }}" class="sidebar-link {{ request()->is('bidang*') ? 'active' : '' }} flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-r-full">
                            <i class="fas fa-sitemap mr-3 text-lg"></i> Kelola Bidang
                        </a>
                    </li>
                    <li class="mt-2">
                        <a href="{{ url('/aset/qrcode') }}" class="sidebar-link {{ request()->is('asets/qrcode') ? 'active' : '' }} flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-r-full">
                            <i class="fas fa-qrcode mr-3 text-lg"></i> QR Code
                        </a>
                    </li>
                    <li class="mt-2">
                        <a href="{{ url('/laporan') }}" class="sidebar-link flex items-center px-6 py-3 text-gray-700 hover:bg-gray-100 hover:text-blue-600 rounded-r-full">
                            <i class="fas fa-file-excel mr-3 text-lg"></i> Laporan
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    {{-- Main Content dengan Bootstrap --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Navbar yang Diperbarui --}}
        <header class="bg-white shadow-sm py-3 px-4 d-flex justify-content-between align-items-center">
            <h1 class="dashboard-title mb-0"></h1>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-2 hover-bg-gray-100" 
                            type="button" 
                            id="dropdownMenuButton" 
                            data-bs-toggle="dropdown" 
                            aria-expanded="false">
                        <span class="text-muted">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</span>
                        <i class="fas fa-user-circle fs-4 text-primary"></i>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-0 border-0" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Profil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        {{-- Konten Halaman --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-light p-4 bootstrap-content">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inisialisasi tooltip Bootstrap jika diperlukan
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Tutup dropdown saat klik di luar
            document.addEventListener('click', function(event) {
                var dropdowns = document.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(function(dropdown) {
                    if (!dropdown.parentNode.contains(event.target)) {
                        dropdown.classList.remove('show');
                    }
                });
            });
        });
    </script>
</body>
</html>