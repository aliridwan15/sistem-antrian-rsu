@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- CSS Khusus Halaman Ini --}}
    <style>
        .stat-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            background: white;
            height: 100%;
            overflow: hidden;
            position: relative;
        }
        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        .stat-icon {
            width: 60px; height: 60px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        .stat-label { color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 2.5rem; font-weight: 800; color: #334155; line-height: 1; }
        
        /* Hiasan Background */
        .stat-card::after {
            content: ""; position: absolute; right: -20px; top: -20px;
            width: 100px; height: 100px; border-radius: 50%;
            opacity: 0.1; z-index: 0;
        }
        .card-content { position: relative; z-index: 1; }
        
        /* Warna-warna */
        .bg-blue-soft { background-color: #e0f2fe; color: #0284c7; }
        .bg-green-soft { background-color: #dcfce7; color: #16a34a; }
        .bg-orange-soft { background-color: #ffedd5; color: #ea580c; }
        .bg-purple-soft { background-color: #f3e8ff; color: #9333ea; }
        
        .card-blue::after { background-color: #0284c7; }
        .card-green::after { background-color: #16a34a; }
        .card-orange::after { background-color: #ea580c; }
        .card-purple::after { background-color: #9333ea; }
    </style>

    {{-- Header Atas --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h4 class="fw-bold mb-1 text-dark">Dashboard Overview</h4>
            <p class="text-muted small mb-0">Laporan statistik antrian pasien.</p>
        </div>
        <div class="d-flex align-items-center gap-3 bg-white px-4 py-2 rounded-pill shadow-sm">
            <div class="text-end lh-1">
                <span class="d-block fw-bold text-dark small">Administrator</span>
                <span class="d-block text-muted" style="font-size: 0.7rem;">Super Admin</span>
            </div>
            <img src="https://ui-avatars.com/api/?name=Admin&background=1B9C85&color=fff" class="rounded-circle" width="35">
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-4">
        
        {{-- Card 1: Total Pasien --}}
        <div class="col-md-6 col-xl-3">
            <div class="stat-card card-blue p-4">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Total Pasien</div>
                            <div class="stat-value">{{ number_format($totalPasien) }}</div>
                        </div>
                        <div class="stat-icon bg-blue-soft">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-database me-1"></i> Keseluruhan Data
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Antrian Hari Ini --}}
        <div class="col-md-6 col-xl-3">
            <div class="stat-card card-green p-4">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Antrian Hari Ini</div>
                            <div class="stat-value">{{ number_format($antrianHariIni) }}</div>
                        </div>
                        <div class="stat-icon bg-green-soft">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-success small fw-bold">
                        <i class="bi bi-arrow-up-circle me-1"></i> {{ date('d M Y') }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Antrian Minggu Ini --}}
        <div class="col-md-6 col-xl-3">
            <div class="stat-card card-orange p-4">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Minggu Ini</div>
                            <div class="stat-value">{{ number_format($antrianMingguIni) }}</div>
                        </div>
                        <div class="stat-icon bg-orange-soft">
                            <i class="bi bi-calendar-week-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-bar-chart-line me-1"></i> Senin - Minggu
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 4: Antrian Bulan Ini --}}
        <div class="col-md-6 col-xl-3">
            <div class="stat-card card-purple p-4">
                <div class="card-content">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label mb-2">Bulan Ini</div>
                            <div class="stat-value">{{ number_format($antrianBulanIni) }}</div>
                        </div>
                        <div class="stat-icon bg-purple-soft">
                            <i class="bi bi-calendar-month-fill"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-muted small">
                        <i class="bi bi-pie-chart-fill me-1"></i> Bulan {{ date('F') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Area Kosong (Placeholder) --}}
    <div class="row mt-5">
        <div class="col-12 text-center text-muted py-5">
            <img src="https://illustrations.popsy.co/gray/success.svg" alt="All Good" height="150" class="mb-3 opacity-50">
            <p>Data antrian belum tersedia.</p>
        </div>
    </div>

@endsection