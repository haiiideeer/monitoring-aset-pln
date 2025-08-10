
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PLN Asset Monitoring System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #0066cc;
            --secondary: #ff6600;
            --dark: #1a1a2e;
            --light: #f5f5f5;
        }
        .btn-primary {
            background-color: var(--primary);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #0055aa;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .sidebar-link.active {
            background-color: rgba(0, 102, 204, 0.1);
            color: var(--primary);
            font-weight: bold;
            border-left: 4px solid var(--primary);
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .dropdown-menu.show { display: block; }
        .dropdown-menu a {
            padding: 12px 16px;
            display: block;
        }
    </style>
</head>
<body class="font-sans bg-gray-50 flex h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-lg flex flex-col justify-between py-6">
        <div>
            <div class="px-6 mb-8 text-center">
               <!-- Ukuran berbeda di layar berbeda -->
<img src="{{ asset('assets/images/foto.png') }}" alt="Logo PLN" class="h-12 w-auto mx-auto mb-2">
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

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center relative">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
            <div class="relative">
                <div id="profileDropdownBtn" class="flex items-center space-x-2 cursor-pointer p-2 rounded-lg hover:bg-gray-100">
                    <span class="text-gray-700">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</span>
                    <i class="fas fa-user-circle text-2xl text-blue-600"></i>
                </div>
                <div id="profileDropdownMenu" class="dropdown-menu rounded-lg">
                    <a href="#" class="px-4 py-2 hover:bg-gray-100"><i class="fas fa-user mr-2"></i>Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6">Ringkasan Aset</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Total Aset</h3>
                    <p class="text-3xl font-bold text-blue-600">1.250</p>
                    <p class="text-gray-600">Aset terdaftar</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Aset Aktif</h3>
                    <p class="text-3xl font-bold text-green-600">1.180</p>
                    <p class="text-gray-600">Beroperasi normal</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">Bidang</h3>
                    <p class="text-3xl font-bold text-orange-600">70</p>
                    <p class="text-gray-600">total Bidang</p>
                </div>
            </div>

            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Laporan Aset per Tahun Berdasarkan Bidang</h3>
                <div class="relative h-96">
                    <canvas id="assetReportChart"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script>
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdownMenu = document.getElementById('profileDropdownMenu');

        profileDropdownBtn?.addEventListener('click', () => {
            profileDropdownMenu.classList.toggle('show');
        });

        window.addEventListener('click', function(event) {
            if (!event.target.closest('#profileDropdownBtn')) {
                profileDropdownMenu.classList.remove('show');
            }
        });

        // Chart.js
        new Chart(document.getElementById('assetReportChart'), {
            type: 'bar',
            data: {
                labels: ['Transmisi', 'Distribusi', 'Pembangkitan', 'Administrasi'],
                datasets: [
                    {
                        label: 'Jumlah Aset 2023',
                        data: [800, 1500, 500, 200],
                        backgroundColor: 'rgba(0, 102, 204, 0.6)',
                        borderColor: 'rgba(0, 102, 204, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Jumlah Aset 2024',
                        data: [850, 1600, 550, 220],
                        backgroundColor: 'rgba(255, 102, 0, 0.6)',
                        borderColor: 'rgba(255, 102, 0, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Jumlah Aset' }
                    },
                    x: {
                        title: { display: true, text: 'Bidang' }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Perbandingan Jumlah Aset per Bidang (2023 vs 2024)',
                        font: { size: 16 }
                    }
                }
            }
        });
    </script>
</body>
</html>
