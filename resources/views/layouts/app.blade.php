<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PLN Monitoring Aset') }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Bootstrap CSS (hanya untuk konten utama) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

         @livewireStyles
         @stack('styles')

    <style>
        :root {
            --primary: #0066cc;
            --secondary: #ff6600;
            --dark: #1a1a2e;
            --light: #f5f5f5;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 60px;
        }
        
        /* Layout Konsisten */
        .app-container {
            display: flex;
            min-height: 100vh;
            background-color: #f5f5f5;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background-color: white;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 10;
            transition: width 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }
        
        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
            white-space: nowrap;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-brand {
            padding: 20px 10px;
        }
        
        .sidebar.collapsed .brand-text {
            display: none;
        }
        
        .sidebar.collapsed .brand-logo {
            width: 30px !important;
            height: auto;
        }
        
        .sidebar-nav {
            padding: 15px 0;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 5px 10px;
            color: #555;
            border-radius: 6px;
            transition: all 0.3s;
            white-space: nowrap;
            text-decoration: none;
        }
        
        .sidebar.collapsed .sidebar-link {
            padding: 12px 18px;
            justify-content: center;
        }
        
        .sidebar-link:hover {
            background-color: rgba(0, 102, 204, 0.1);
            color: var(--primary);
            text-decoration: none;
        }
        
        .sidebar-link.active {
            background-color: rgba(0, 102, 204, 0.1);
            color: var(--primary);
            font-weight: 600;
            border-left: 4px solid var(--primary);
        }
        
        .sidebar-link i {
            width: 24px;
            text-align: center;
            margin-right: 10px;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed .sidebar-link i {
            margin-right: 0;
        }
        
        .sidebar.collapsed .sidebar-link-text {
            display: none;
        }
        
        /* Tooltip untuk sidebar collapsed */
        .sidebar.collapsed .sidebar-link {
            position: relative;
        }
        
        .sidebar.collapsed .sidebar-link:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background-color: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 1000;
            margin-left: 10px;
            font-size: 14px;
        }
        
        /* Header Styles */
        .main-header {
            height: 70px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 25px;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 5;
            transition: left 0.3s ease;
        }
        
        .sidebar.collapsed + .main-wrapper .main-header {
            left: var(--sidebar-collapsed-width);
        }
        
        /* Toggle Button */
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #555;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.2s;
        }
        
        .sidebar-toggle:hover {
            background-color: #f8f9fa;
            color: var(--primary);
        }
        
        /* Main Content Styles */
        .main-wrapper {
            flex: 1;
            transition: all 0.3s ease;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 20px;
            flex: 1;
            min-height: calc(100vh - 70px);
            transition: margin-left 0.3s ease;
        }
        
        .sidebar.collapsed + .main-wrapper .main-content {
            margin-left: var(--sidebar-collapsed-width);
        }
        
        /* Dropdown Styles */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            border-radius: 6px;
            z-index: 1000;
        }
        
        .dropdown-menu.show {
            display: block;
        }
        
        .dropdown-item {
            padding: 10px 15px;
            display: block;
            color: #333;
            transition: background-color 0.2s;
            text-decoration: none;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
            text-decoration: none;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 6px;
            transition: background-color 0.2s;
        }
        
        .user-dropdown:hover {
            background-color: #f8f9fa;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 10px;
            color: var(--primary);
            font-size: 1.2rem;
        }

        /* Search Bar Styles */
        .search-container {
            position: relative;
            width: 300px;
            margin-right: 20px;
        }
        
        .search-input {
            width: 100%;
            padding: 8px 40px 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(0, 102, 204, 0.2);
        }
        
        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
            cursor: pointer;
        }
        
        .search-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 10px;
            z-index: 100;
            display: none;
        }
        
        .search-options.show {
            display: block;
        }
        
        .search-filter {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 10px;
        }
        
        .filter-pill {
            padding: 4px 10px;
            background: #f0f5ff;
            border: 1px solid #d0deff;
            border-radius: 16px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .filter-pill:hover, .filter-pill.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .search-button {
            width: 100%;
            padding: 8px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .search-button:hover {
            background: #0052a3;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-header {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            /* Overlay untuk mobile */
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 9;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .search-container {
                width: 200px;
                margin-right: 10px;
            }
        }
    </style>
</head>
@stack('scripts')
<body class="font-sans">
    <div class="app-container">
        {{-- Overlay untuk mobile --}}
        <div id="sidebarOverlay" class="sidebar-overlay"></div>
        
        {{-- Sidebar --}}
        <aside id="sidebar" class="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('asset/images/foto.png') }}" alt="Logo PLN" class="brand-logo h-12 w-auto mx-auto mb-2">
                <span class="brand-text text-xl font-bold text-blue-800">Asset Monitoring</span>
            </div>
            
            <nav class="sidebar-nav">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                           data-tooltip="Dashboard">
                            <i class="fas fa-tachometer-alt"></i>
                            <span class="sidebar-link-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('assets.index') }}" 
                           class="sidebar-link {{ request()->is('assets*') ? 'active' : '' }}"
                           data-tooltip="Kelola Aset">
                            <i class="fas fa-boxes"></i>
                            <span class="sidebar-link-text">Kelola Aset</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('bidang.index') }}" 
                           class="sidebar-link {{ request()->is('bidang*') ? 'active' : '' }}"
                           data-tooltip="Kelola Bidang">
                            <i class="fas fa-sitemap"></i>
                            <span class="sidebar-link-text">Kelola Bidang</span>
                        </a>
                    </li>
                 
                    <li>
                        <a href="{{ url('/laporan') }}" 
                           class="sidebar-link"
                           data-tooltip="Kelola Laporan">
                            <i class="fas fa-file-excel"></i>
                            <span class="sidebar-link-text">Kelola Laporan</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        {{-- Main Content Area --}}
        <div class="main-wrapper flex-1">
            {{-- Header --}}
            <header class="main-header">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="sidebar-toggle mr-4">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    {{-- Search Bar --}}
                    <div class="search-container">
                        <input type="text" id="searchInput" class="search-input" placeholder="Cari aset..." autocomplete="off">
                        <div class="search-icon" id="searchToggle">
                            <i class="fas fa-search"></i>
                        </div>
                            
                    </div>
                </div>
                
                <div class="relative">
                    <div id="profileDropdownBtn" class="user-dropdown">
                        <span class="text-gray-700">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</span>
                        <div class="user-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    </div>
                    <div id="profileDropdownMenu" class="dropdown-menu">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item w-full text-left">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Check if we're on mobile
        function isMobile() {
            return window.innerWidth <= 768;
        }
        
        // Toggle sidebar
        function toggleSidebar() {
            if (isMobile()) {
                // Mobile behavior
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            } else {
                // Desktop behavior
                sidebar.classList.toggle('collapsed');
                // Save state to localStorage
                localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
            }
        }
        
        // Event listeners
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }
        
        // Close sidebar on overlay click (mobile)
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }
        
        // Close sidebar on window resize if mobile
        window.addEventListener('resize', () => {
            if (isMobile()) {
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            } else {
                // Restore desktop state
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                }
            }
        });
        
        // Load saved state on page load (desktop only)
        document.addEventListener('DOMContentLoaded', () => {
            if (!isMobile()) {
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                }
            }
        });
        
        // Profile dropdown functionality
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdownMenu = document.getElementById('profileDropdownMenu');

        if (profileDropdownBtn) {
            profileDropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdownMenu.classList.toggle('show');
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (profileDropdownMenu?.classList.contains('show')) {
                if (!profileDropdownBtn.contains(e.target)) {
                    profileDropdownMenu.classList.remove('show');
                }
            }
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchToggle = document.getElementById('searchToggle');
        const searchOptions = document.getElementById('searchOptions');
        const searchButton = document.getElementById('searchButton');
        const filterPills = document.querySelectorAll('.filter-pill');
        
        let selectedField = 'all'; // Default field untuk pencarian
        
        // Toggle search options
        if (searchToggle) {
            searchToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                searchOptions.classList.toggle('show');
            });
        }
        
        // Close search options when clicking outside
        document.addEventListener('click', (e) => {
            if (searchOptions.classList.contains('show') && 
                !searchInput.contains(e.target) && 
                !searchToggle.contains(e.target) &&
                !searchOptions.contains(e.target)) {
                searchOptions.classList.remove('show');
            }
        });
        
        // Filter pill selection
        filterPills.forEach(pill => {
            pill.addEventListener('click', () => {
                // Remove active class from all pills
                filterPills.forEach(p => p.classList.remove('active'));
                
                // Add active class to clicked pill
                pill.classList.add('active');
                
                // Update selected field
                selectedField = pill.getAttribute('data-field');
            });
        });
        
        // Search button functionality
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }
        
        // Allow pressing Enter to search
        if (searchInput) {
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        }
        
        // Perform search function
        function performSearch() {
            const searchTerm = searchInput.value.trim();
            
            if (!searchTerm) {
                alert('Masukkan kata kunci pencarian');
                return;
            }
            
            // Redirect to assets page with search parameters
            let url = "{{ route('assets.index') }}";
            
            
            // Add search parameters based on selected field
            if (selectedField === 'all') {
                // Redirect with general search parameter
                window.location.href = `${url}?search=${encodeURIComponent(searchTerm)}`;
            } else {
                // Redirect with specific field search parameter
                window.location.href = `${url}?${selectedField}=${encodeURIComponent(searchTerm)}`;
            }
            
            // Close search options
            searchOptions.classList.remove('show');
        }
    </script>
      @livewireScripts
      @stack('scripts')
    
</body>

</html>