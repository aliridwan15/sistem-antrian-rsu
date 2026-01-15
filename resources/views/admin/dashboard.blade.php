@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- CSS Khusus Halaman Ini (Optional) --}}
    <style>
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            transition: 0.3s;
            background: white;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .icon-box {
            width: 50px; height: 50px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
        }
    </style>

    {{-- Header Atas --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0">Dashboard Admin</h4>
            <p class="text-muted small">Ringkasan data rumah sakit hari ini</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small">Halo, <strong>Admin</strong></span>
            <img src="https://ui-avatars.com/api/?name=Admin&background=1B9C85&color=fff" class="rounded-circle" width="40">
        </div>
    </div>

    {{-- Statistik Cards --}}
    <div class="row g-4 mb-5">
        {{-- Card 1 --}}
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Total Pasien</p>
                        <h3 class="fw-bold mb-0">1,240</h3>
                    </div>
                    <div class="icon-box bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- Card 2 --}}
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Antrian Hari Ini</p>
                        <h3 class="fw-bold mb-0 text-success">45</h3>
                    </div>
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- Card 3 --}}
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Dokter Tersedia</p>
                        <h3 class="fw-bold mb-0">12</h3>
                    </div>
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-person-lines-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        {{-- Card 4 --}}
        <div class="col-md-3">
            <div class="card stat-card p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted small mb-1">Poli Aktif</p>
                        <h3 class="fw-bold mb-0">15</h3>
                    </div>
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="bi bi-hospital"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Antrian Terbaru --}}
    <div class="card stat-card border-0">
        <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Antrian Terbaru Masuk</h5>
            <button class="btn btn-sm btn-outline-success rounded-pill">Lihat Semua</button>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No. Antrian</th>
                            <th>Nama Pasien</th>
                            <th>Poli Tujuan</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">G-102</td>
                            <td>Budi Santoso</td>
                            <td>Poli Gigi</td>
                            <td>drg. Nanda Putri</td>
                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">A-205</td>
                            <td>Siti Aminah</td>
                            <td>Poli Anak</td>
                            <td>dr. Budi Santoso, Sp.A</td>
                            <td><span class="badge bg-success">Selesai</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">J-008</td>
                            <td>Ahmad Dhani</td>
                            <td>Poli Jantung</td>
                            <td>dr. Hartono, Sp.JP</td>
                            <td><span class="badge bg-danger">Batal</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary"><i class="bi bi-eye"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection