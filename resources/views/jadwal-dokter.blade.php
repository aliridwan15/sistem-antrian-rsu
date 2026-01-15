@extends('layouts.app')

@section('title', 'Jadwal Dokter - RSU Anna Medika Madura')

@section('content')

{{-- STYLE KHUSUS HALAMAN INI --}}
<style>
    .jadwal-header {
        position: relative;
        padding: 80px 0 60px 0;
        text-align: center;
        overflow: hidden;
        background-color: #f8f9fa;
    }

    .jadwal-header::before {
        content: "";
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: url('{{ asset('images/dokter.jpg') }}') center/cover no-repeat;
        opacity: 0.3;
        z-index: 0;
    }

    .jadwal-header .container {
        position: relative;
        z-index: 1;
    }

    .btn-hari {
        border-radius: 50px;
        padding: 8px 25px;
        border: 2px solid #1B9C85;
        color: #1B9C85;
        background-color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        margin: 5px;
    }

    .btn-hari:hover, .btn-hari.active {
        background-color: #1B9C85;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(27, 156, 133, 0.3);
    }

    .poli-title {
        color: #1B9C85;
        font-weight: bold;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 10px;
        margin-bottom: 15px;
        display: inline-block;
    }

    .dokter-item {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border-left: 5px solid #1B9C85;
        transition: 0.3s;
    }
    
    .dokter-item:hover {
        transform: translateX(5px);
    }

    .jam-praktek {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .day-content {
        display: none;
        animation: fadeIn 0.5s ease-in-out;
    }

    .day-content.active {
        display: block;
    }

    /* Styling tambahan untuk Badge NB */
    .badge-nb {
        font-size: 0.75rem;
        padding: 5px 10px;
        border-radius: 5px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 8px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<section class="jadwal-header">
    <div class="container">
        <h2 class="fw-bold display-6 mb-4">Temukan Jadwal Dokter Spesialis</h2>
        
        <div class="d-flex flex-wrap justify-content-center mt-4">
            {{-- Loop untuk membuat button hari secara otomatis --}}
            @foreach(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'] as $hari)
                <button class="btn btn-hari {{ $hari == 'senin' ? 'active' : '' }}" 
                        onclick="filterHari('{{ $hari }}', this)">
                    {{ ucfirst($hari) }}
                </button>
            @endforeach
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container" id="jadwal-container">
        
        {{-- Loop utama untuk Konten Hari --}}
        @foreach($jadwal as $hari => $daftarDokter)
            <div id="{{ $hari }}" class="day-content {{ $hari == 'senin' ? 'active' : '' }}">
                <div class="row">
                    @foreach($daftarDokter as $data)
                        <div class="col-md-6 mb-4">
                            <h4 class="poli-title"><i class="bi {{ $data['icon'] }} me-2"></i>{{ $data['poli'] }}</h4>
                            <div class="dokter-item">
                                <h5 class="fw-bold mb-1">{{ $data['dokter'] }}</h5>
                                
                                {{-- Tampilan NB (Janji Temu) jika data tersedia --}}
                                @if(!empty($data['note']))
                                    <div class="badge-nb bg-warning text-dark">
                                        <i class="bi bi-exclamation-circle me-1"></i> NB: {{ $data['note'] }}
                                    </div>
                                @endif

                                <div class="jam-praktek"><i class="bi bi-clock me-1"></i> {{ $data['jam'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="alert alert-light border text-center mt-3 shadow-sm rounded-4">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i> 
                Jadwal dapat berubah sewaktu-waktu tanpa pemberitahuan. Silakan hubungi bagian informasi untuk konfirmasi.
            </small>
        </div>
    </div>
</section>

<script>
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
    }
</script>

@endsection