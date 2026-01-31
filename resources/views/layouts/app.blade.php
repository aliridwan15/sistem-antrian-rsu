<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RSU Anna Medika Madura')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Segoe UI', sans-serif; margin:0; padding:0; background-color: #f5f5f5; display: flex; flex-direction: column; min-height: 100vh; }

        /* --- GLOBAL BUTTON --- */
        .btn-rs { background-color: #1B9C85; color: white; border-radius: 30px; padding: 8px 25px; transition: 0.3s; border: none; white-space: nowrap; font-weight: 600; }
        .btn-rs:hover { background-color: #14806c; color: white; box-shadow: 0 4px 10px rgba(27, 156, 133, 0.3); }

        /* --- NAVBAR STYLES --- */
        .navbar { padding: 10px 0; }
        .navbar-logo { height: 45px; margin-right: 15px; }

        /* Menu Navigasi Utama */
        .navbar-nav .nav-link { 
            font-weight: 500; color: #333; margin-right: 15px; position: relative; white-space: nowrap; 
        }
        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active { color: #1B9C85; }
        
        /* Garis Bawah Animasi */
        .navbar-nav .nav-link::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: 5px; left: 0;
            background-color: #1B9C85; transition: width 0.3s ease-in-out;
        }
        .navbar-nav .nav-link:hover::after, .navbar-nav .nav-link.active::after { width: 100%; }

        /* --- LAYOUT RESPONSIVE TANPA HAMBURGER --- */
        .navbar-expand-lg .navbar-collapse { display: flex !important; flex-basis: auto; }
        .navbar-nav { flex-direction: row; align-items: center; flex-wrap: nowrap; }

        /* Container Scrollable */
        .nav-scroller-wrapper {
            display: flex; align-items: center; flex-grow: 1; overflow-x: auto; 
            scrollbar-width: none; -ms-overflow-style: none; padding-right: 10px;
        }
        .nav-scroller-wrapper::-webkit-scrollbar { display: none; }

        /* --- USER DROPDOWN STYLE --- */
        .user-dropdown-link { 
            color: #1B9C85 !important; padding: 6px 12px; border-radius: 30px; 
            transition: all 0.3s ease; white-space: nowrap; display: flex; align-items: center;
        }
        .user-dropdown-link:hover, .user-dropdown-link[aria-expanded="true"] { 
            background-color: #e0f2ef; color: #14806c !important; 
        }
        .user-dropdown-link[aria-expanded="true"] .bi-chevron-down { transform: rotate(180deg); }
        .bi-chevron-down { transition: transform 0.3s; font-size: 0.7rem; margin-left: 5px; }
        
        .dropdown-menu { border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-top: 10px; }
        .dropdown-toggle::after { content: none; }

        /* --- HERO SECTION --- */
        .hero { position: relative; padding: 100px 0; min-height: 84vh; display: flex; align-items: center; justify-content: center; background: url('{{ asset('images/rsanna.jpg') }}') center/cover no-repeat; overflow: hidden; }
        .hero::before { content: ""; position: absolute; inset: 0; background: rgba(255, 255, 255, 0.75); z-index: 0; }
        .hero .container, .hero .container-fluid { position: relative; z-index: 2; }

        /* --- CONTACT & SOCIAL ICONS --- */
        .contact-wrapper { display: flex; align-items: center; gap: 8px; margin-left: 15px; border-left: 1px solid #ddd; padding-left: 15px; }
        .contact-text-wrapper { text-align: right; font-size: 0.85rem; line-height: 1.2; margin-right: 5px; }
        .contact-number { color: #212529; text-decoration: none; transition: color 0.3s ease; }
        .contact-number:hover { color: #1B9C85; }
        .social-link { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s ease; text-decoration: none; }
        .social-link:hover { background-color: #e0f2ef; }
        .social-link i { font-size: 1.2rem; color: #1B9C85; }

        /* --- POLI CARD HOVER --- */
        .poli-card { transition: all 0.3s ease; cursor: pointer; background-color: white; }
        .poli-card:hover { background-color: #1B9C85; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(27, 156, 133, 0.4) !important; }
        .poli-card:hover i, .poli-card:hover p { color: white !important; }
        .poli-card p { position: relative; display: inline-block; padding-bottom: 3px; }
        .poli-card p::after { content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 0; background-color: white; transition: width 0.3s ease-in-out; }
        .poli-card:hover p::after { width: 100%; }

        /* --- FOOTER --- */
        footer { background-color: white; padding-top: 3rem; padding-bottom: 1.5rem; margin-top: auto; border-top: 1px solid #e9ecef; }
        .footer-link { color: #1B9C85; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .footer-link:hover { color: #14806c; text-decoration: underline; }

        @media (max-width: 991px) {
            .contact-text-wrapper, .social-link { display: none; }
            .contact-wrapper { border: none; padding: 0; margin: 0; }
            .navbar-logo { height: 35px; margin-right: 10px; }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
        <div class="container-fluid px-3 px-lg-5"> 
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logors.png') }}" alt="Logo" class="navbar-logo"> 
            </a>

            <div class="nav-scroller-wrapper">
                <ul class="navbar-nav me-auto mb-0"> 
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jadwal.dokter') ? 'active' : '' }}" href="{{ route('jadwal.dokter') }}">Jadwal Dokter</a>
                    </li>
                    
                    {{-- MENU 1: Tiket Saya (Cookie Device) --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tiket.show') ? 'active' : '' }}" href="{{ route('tiket.show') }}">Tiket Saya</a>
                    </li>

                    {{-- MENU 2: Cek Tiket (Pencarian Umum) --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tiket.check') ? 'active' : '' }}" href="{{ route('tiket.check') }}">Cek Tiket</a>
                    </li>
                </ul>
            </div>

            <ul class="navbar-nav ms-auto mb-0 align-items-center">
                
                @auth
                    {{-- TAMPILAN JIKA ADMIN LOGIN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold user-dropdown-link" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-shield-lock-fill me-1 fs-5"></i> 
                            <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span> <i class="bi bi-chevron-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- TAMPILAN JIKA PASIEN (GUEST) --}}
                    {{-- TOMBOL HIJAU 'TIKET SAYA' DI KANAN DIHAPUS (Sesuai Request) --}}
                    {{-- Menu 'Tiket Saya' dan 'Cek Tiket' sudah ada di Navbar kiri --}}
                @endauth

                {{-- Kontak Info (Desktop Only) --}}
                <li class="nav-item d-none d-lg-block">
                    <div class="contact-wrapper">
                        <div class="contact-text-wrapper">
                            <span class="text-muted small">Hubungi Kami:</span><br>
                            <a href="tel:03199303942" class="fw-bold contact-number">031 99303942</a>
                        </div>
                        <a href="https://wa.me/6282278888001" target="_blank" class="social-link" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                        <a href="https://www.instagram.com/annamedikamadura" target="_blank" class="social-link" title="Instagram"><i class="bi bi-instagram"></i></a>
                    </div>
                </li>

            </ul>

        </div>
    </nav>

    <main style="padding-top: 80px;">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h5 class="fw-bold mb-2">RSU Anna Medika Madura</h5>
                    <p class="mb-1 text-dark">WhatsApp 082278888001 - Telp (031) 35901234</p>
                    <p class="mb-3 text-muted">Jl. RE Martadinata No. 10 Mlajah Bangkalan</p>
                    <a href="https://www.google.com/maps/place/RSU+Anna+Medika+Madura/@-7.0496739,112.729804,15z/data=!4m5!3m4!1s0x0:0x79ff9f7e3ebc3566!8m2!3d-7.0496739!4d112.729804?sa=X&ved=2ahUKEwjwtvjp1qP8AhVW3nMBHWZZCIwQ_BJ6BAhfEAg&coh=164777&entry=tt&shorturl=1" target="_blank" class="footer-link">Alamat Kami <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
            </div>
            <div class="text-center mt-4 pt-3 border-top d-flex flex-column align-items-center gap-1">
                <small class="text-muted">© {{ date('Y') }} RSU Anna Medika Madura. All rights reserved.</small>
                
                {{-- Link Login Admin Tersembunyi/Kecil di Footer --}}
                @guest
                    <a href="{{ route('login') }}" class="text-decoration-none text-muted" style="font-size: 0.7rem;">Login Staff</a>
                @endguest
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>