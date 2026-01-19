<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - RSU Anna Medika</title>
    
    {{-- Bootstrap & Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --rs-green: #1B9C85;
            --rs-green-light: #e0f2ef;
            --sidebar-width: 260px;
            --text-color: #334155;
            --topbar-height: 60px; /* Tinggi navbar mobile */
        }

        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            overflow-x: hidden; /* Mencegah scroll horizontal */
        }

        /* --- SIDEBAR STYLE --- */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            color: var(--text-color);
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1050; /* Di atas elemen lain */
            padding-top: 20px;
            border-right: 1px solid #e2e8f0;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-brand {
            padding: 0 20px 20px 20px;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .sidebar-brand img {
            max-width: 100%;
            height: auto;
            max-height: 60px;
            object-fit: contain;
        }

        .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 12px 25px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease-in-out;
            margin: 4px 15px;
            border-radius: 8px;
            text-decoration: none;
        }

        .nav-link i {
            font-size: 1.25rem;
            margin-right: 12px;
            color: #94a3b8;
            transition: 0.2s;
        }

        .nav-link:hover {
            color: var(--rs-green);
            background-color: #f1fbf9;
        }
        
        .nav-link.active {
            color: var(--rs-green);
            background-color: var(--rs-green-light);
            font-weight: 600;
        }

        .nav-link:hover i, .nav-link.active i {
            color: var(--rs-green);
        }

        .submenu-link {
            padding-left: 3.5rem !important;
            font-size: 0.9rem;
            color: #64748b;
            margin: 2px 15px;
        }
        .submenu-link:hover {
            color: var(--rs-green);
            background-color: transparent;
            transform: translateX(5px);
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 30px;
            transition: margin-left 0.3s ease-in-out;
            width: 100%; /* Pastikan full width */
        }

        /* --- MOBILE TOGGLE & OVERLAY --- */
        .mobile-toggle {
            display: none; /* Hidden di desktop */
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1060;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            color: var(--rs-green);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            cursor: pointer;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        /* --- RESPONSIVE MEDIA QUERIES --- */
        @media (max-width: 991.98px) {
            /* 1. Sembunyikan sidebar secara default (geser ke kiri) */
            .sidebar {
                transform: translateX(-100%);
            }

            /* 2. Main content jadi full width */
            .main-content {
                margin-left: 0;
                padding: 70px 20px 20px 20px; /* Padding top ditambah agar tidak ketutup tombol toggle */
            }

            /* 3. Tampilkan tombol toggle */
            .mobile-toggle {
                display: block;
            }

            /* 4. Class 'show' untuk menampilkan sidebar */
            .sidebar.show {
                transform: translateX(0);
            }

            /* 5. Tampilkan overlay saat sidebar terbuka */
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    {{-- TOMBOL TOGGLE MOBILE --}}
    <button class="mobile-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list fs-4"></i>
    </button>

    {{-- OVERLAY GELAP --}}
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- 1. SIDEBAR --}}
    <nav class="sidebar" id="sidebarMenu">
        
        {{-- BAGIAN LOGO --}}
        <div class="sidebar-brand">
            <img src="{{ asset('images/logors.png') }}" alt="Logo RSU Anna Medika">
        </div>

        <div class="nav flex-column">
            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>

            {{-- Antrian Masuk (Dropdown) --}}
            <a href="#submenuPoli" data-bs-toggle="collapse" class="nav-link collapsed d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="bi bi-calendar-check-fill"></i> Antrian Masuk
                </div>
                <i class="bi bi-chevron-down small" style="font-size: 0.8rem;"></i>
            </a>
            
            {{-- Isi Dropdown Poli --}}
            <div class="collapse {{ request()->is('admin/antrian*') ? 'show' : '' }}" id="submenuPoli">
                <div class="py-1">
                    @if(isset($polis) && $polis->count() > 0)
                        @foreach($polis as $p)
                            <a href="#" class="nav-link submenu-link">
                                {{ $p->name }} 
                            </a>
                        @endforeach
                    @else
                        <span class="text-muted small ps-5 d-block py-2">Data Poli tidak ada</span>
                    @endif
                </div>
            </div>

            {{-- Menu Data Dokter --}}
            <a href="{{ route('admin.dokter.index') }}" class="nav-link {{ request()->routeIs('admin.dokter*') ? 'active' : '' }}">
                <i class="bi bi-person-video2"></i> Data Dokter
            </a>

            {{-- Menu Data Poli --}}
            <a href="#" class="nav-link">
                <i class="bi bi-clipboard-pulse"></i> Data Poli
            </a>
            
            {{-- Logout Section --}}
            <div class="mt-auto pt-4 px-3 pb-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2" style="border-radius: 8px;">
                        <i class="bi bi-box-arrow-left"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- 2. MAIN CONTENT --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Script Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Script Toggle Sidebar --}}
    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const overlay = document.querySelector('.sidebar-overlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        function closeSidebar() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }
    </script>
</body>
</html>