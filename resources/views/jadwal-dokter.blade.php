@extends('layouts.app')

@section('title', 'Jadwal Dokter - RSU Anna Medika Madura')

@section('content')

{{-- STYLE KHUSUS HALAMAN INI --}}
<style>
    /* --- Header --- */
    .jadwal-header {
        position: relative; 
        padding: 60px 0 50px 0; 
        background-color: #f8f9fa;
        overflow: hidden;
        text-align: center; 
    }

    .jadwal-header::before {
        content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: url('{{ asset('images/dokter.jpg') }}') center/cover no-repeat; 
        opacity: 0.15; 
        z-index: 0;
    }

    .jadwal-header .container { 
        position: relative; 
        z-index: 1; 
        display: flex;
        flex-direction: column;
        align-items: center; /* Konten Header Rata Tengah */
        justify-content: center;
    }

    /* --- Style Tombol Hari --- */
    .btn-hari {
        border-radius: 50px; padding: 8px 25px; border: 2px solid #1B9C85;
        color: #1B9C85; background-color: rgba(255, 255, 255, 0.9);
        font-weight: 600; transition: all 0.3s ease; margin: 5px; font-size: 1rem;
    }
    .btn-hari:hover, .btn-hari.active {
        background-color: #1B9C85; color: white;
        transform: translateY(-2px); box-shadow: 0 4px 10px rgba(27, 156, 133, 0.3);
    }

    /* --- Style Dropdown Filter --- */
    .filter-wrapper {
        margin-top: 25px; min-width: 300px;
    }

    .form-select-poli {
        border-radius: 50px; 
        padding: 10px 40px 10px 20px;
        font-size: 0.95rem;           
        border: 2px solid #1B9C85;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        font-weight: 600; color: #444;
        background-color: rgba(255, 255, 255, 0.95);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%231B9C85' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-size: 14px;
        cursor: pointer;
        text-align: left; 
    }
    .form-select-poli:focus { border-color: #14806c; box-shadow: 0 0 0 0.2rem rgba(27, 156, 133, 0.25); }

    /* Card & List Dokter */
    .card-jadwal {
        background: white; border-radius: 12px; padding: 0; margin-bottom: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-left: 5px solid #1B9C85;
        height: 100%; overflow: hidden; position: relative;
        text-align: left;
    }
    .poli-header {
        padding: 15px 20px; background-color: #ffffff; border-bottom: 1px solid #eeeeee;
        color: #1B9C85 !important; font-weight: bold; font-size: 1.1rem;
        display: flex; align-items: center; position: relative; z-index: 10;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    .dokter-list-container { padding: 0; background-color: #fff; }
    .dokter-row {
        padding: 15px 20px; border-bottom: 1px dashed #f0f0f0; transition: all 0.2s ease;
        background-color: white; cursor: default; position: relative; z-index: 1; color: #333; 
    }
    .dokter-row:last-child { border-bottom: none; }
    .dokter-row:hover { background-color: #f0fdfa; padding-left: 28px; border-left: 5px solid #1B9C85; }
    .nama-dokter { font-weight: 700; color: #333; font-size: 1rem; margin-bottom: 5px; transition: color 0.2s; }
    .dokter-row:hover .nama-dokter { color: #1B9C85; }
    .jam-praktek { font-size: 0.9rem; color: #6c757d; font-weight: 500; display: flex; align-items: center; }
    .day-content { display: none; animation: fadeIn 0.4s ease-in-out; }
    .day-content.active { display: block; }
    .badge-nb {
        font-size: 0.7rem; padding: 4px 8px; border-radius: 4px; background-color: #fff8e1;
        color: #856404; border: 1px solid #ffeeba; display: inline-block; margin-top: 8px;
    }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<section class="jadwal-header">
    <div class="container">
        
        {{-- 1. JUDUL --}}
        <h2 class="fw-bold display-5 mb-4 text-dark" style="text-shadow: 2px 2px 4px rgba(255,255,255,0.8);">
            Temukan Jadwal Dokter Spesialis
        </h2>

        {{-- 2. TOMBOL HARI (Posisi Tengah) --}}
        <div class="d-flex flex-wrap justify-content-center">
            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                <button class="btn btn-hari {{ $hari == 'senin' ? 'active' : '' }}" 
                        onclick="filterHari('{{ $hari }}', this)">
                    {{ ucfirst($hari) }}
                </button>
            @endforeach
        </div>

        {{-- 3. DROPDOWN FILTER (Otomatis terpilih jika ada parameter) --}}
        <div class="filter-wrapper">
            <select id="filterPoli" class="form-select form-select-poli" onchange="applyFilter()">
                <option value="all">Tampilkan Semua Poliklinik</option>
                @foreach($polis as $p)
                    <option value="{{ $p->name }}" {{ isset($selectedPoli) && $selectedPoli == $p->name ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</section>

<section class="py-5 bg-light bg-opacity-25">
    <div class="container" id="jadwal-container">
        
        @foreach($jadwal as $hari => $daftarDokter)
            <div id="{{ $hari }}" class="day-content {{ $hari == 'senin' ? 'active' : '' }}">
                
                @if(empty($daftarDokter))
                     <div class="text-center py-5 text-muted">
                        <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>
                        Belum ada jadwal dokter untuk hari ini.
                     </div>
                @else
                    <div class="row" id="row-{{ $hari }}">
                        @php
                            $perPoli = collect($daftarDokter)->groupBy('poli');
                        @endphp

                        @foreach($perPoli as $namaPoli => $dokters)
                            @php $firstData = $dokters->first(); @endphp
                            
                            <div class="col-md-6 col-lg-4 mb-4 item-poli" data-poli="{{ $namaPoli }}">
                                <div class="card-jadwal h-100">
                                    <div class="poli-header">
                                        <i class="bi {{ $firstData['icon'] }} me-2 fs-5"></i>
                                        {{ $namaPoli }}
                                    </div>

                                    <div class="dokter-list-container">
                                        @foreach($dokters as $data)
                                            <div class="dokter-row">
                                                <div class="nama-dokter">{{ $data['dokter'] }}</div>
                                                <div class="jam-praktek">
                                                    <i class="bi bi-clock me-2 text-success"></i> 
                                                    {{ $data['jam'] }}
                                                </div>
                                                @if(!empty($data['note']))
                                                    <div class="badge-nb">
                                                        <i class="bi bi-info-circle-fill me-1"></i> {{ $data['note'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Pesan No Result saat Filter Poli --}}
                    <div id="no-result-{{ $hari }}" class="text-center py-5 text-muted d-none">
                        <i class="bi bi-search fs-1 d-block mb-2 opacity-50"></i>
                        Tidak ditemukan jadwal untuk poli tersebut di hari {{ ucfirst($hari) }}.
                    </div>
                @endif

            </div>
        @endforeach

        <div class="alert alert-light border text-center mt-4 shadow-sm rounded-4">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i> 
                Jadwal dapat berubah sewaktu-waktu tanpa pemberitahuan. Silakan hubungi bagian informasi untuk konfirmasi.
            </small>
        </div>
    </div>
</section>

<script>
    // Jalankan filter otomatis saat halaman dimuat jika ada poli yang dipilih
    document.addEventListener("DOMContentLoaded", function() {
        applyFilter();
    });

    // 1. FILTER HARI (TAB)
    function filterHari(hariId, element) {
        const contents = document.querySelectorAll('.day-content');
        contents.forEach(content => {
            content.classList.remove('active');
        });

        const target = document.getElementById(hariId);
        if (target) {
            target.classList.add('active');
        }

        const buttons = document.querySelectorAll('.btn-hari');
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });
        element.classList.add('active');

        applyFilter();
    }

    // 2. FILTER POLIKLINIK (DROPDOWN)
    function applyFilter() {
        const selectedPoli = document.getElementById('filterPoli').value;
        
        const activeTab = document.querySelector('.day-content.active');
        if (!activeTab) return;

        const items = activeTab.querySelectorAll('.item-poli');
        const noResultMsg = activeTab.querySelector('[id^="no-result-"]');
        let visibleCount = 0;

        items.forEach(item => {
            const itemPoliName = item.getAttribute('data-poli');
            
            if (selectedPoli === 'all' || itemPoliName === selectedPoli) {
                item.style.display = 'block'; 
                visibleCount++;
            } else {
                item.style.display = 'none'; 
            }
        });

        if (visibleCount === 0 && items.length > 0) {
            if(noResultMsg) noResultMsg.classList.remove('d-none');
        } else {
            if(noResultMsg) noResultMsg.classList.add('d-none');
        }
    }
</script>

@endsection