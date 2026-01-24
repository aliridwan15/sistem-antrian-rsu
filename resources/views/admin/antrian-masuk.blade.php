@extends('layouts.admin')

@section('title', 'Antrian Masuk')

@section('content')

<style>
    /* VARS WARNA RSU */
    :root { 
        --rs-green: #1B9C85; 
        --rs-green-dark: #14806c; 
        --rs-green-light: #e0f2ef;
    }

    /* CARD STYLE */
    .card-antrian {
        border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.2s;
    }
    .card-antrian:hover { transform: translateY(-2px); }
    
    /* STATUS BADGE */
    .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; white-space: nowrap; }
    
    .status-menunggu { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .status-dipanggil { background-color: var(--rs-green-light); color: var(--rs-green-dark); border: 1px solid var(--rs-green); animation: pulse-green 2s infinite; }
    .status-selesai { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .status-batal { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }

    /* TOMBOL CUSTOM */
    .btn-panggil { background-color: var(--rs-green); color: white; border: none; transition: 0.3s; }
    .btn-panggil:hover { background-color: var(--rs-green-dark); color: white; box-shadow: 0 4px 8px rgba(27, 156, 133, 0.3); }

    .btn-selesai { background-color: white; color: var(--rs-green); border: 1px solid var(--rs-green); transition: 0.3s; }
    .btn-selesai:hover { background-color: var(--rs-green); color: white; }

    @keyframes pulse-green {
        0% { box-shadow: 0 0 0 0 rgba(27, 156, 133, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(27, 156, 133, 0); }
        100% { box-shadow: 0 0 0 0 rgba(27, 156, 133, 0); }
    }

    /* TABLE STYLING AGAR RAPI */
    .table th { white-space: nowrap; vertical-align: middle; }
    .table td { vertical-align: middle; }
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
</style>

<div class="container-fluid">
    
    {{-- HEADER & FILTER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: var(--rs-green) !important;">
                Antrian Masuk {{ request('poli') ? '- ' . request('poli') : '' }}
            </h4>
            
            {{-- INDIKATOR MODE FILTER --}}
            @if($isDateFiltered)
                <span class="badge bg-warning text-dark border border-warning shadow-sm mb-1">
                    <i class="bi bi-funnel-fill me-1"></i> Mode History
                </span>
                <p class="text-muted small mb-0">Menampilkan <strong>SEMUA STATUS</strong> pada tanggal <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</strong>.</p>
            @else
                <span class="badge bg-success border border-success shadow-sm mb-1">
                    <i class="bi bi-broadcast me-1"></i> Mode Aktif
                </span>
                <p class="text-muted small mb-0">Menampilkan antrian aktif (Menunggu/Dipanggil) untuk <strong>Hari Ini & Masa Depan</strong>.</p>
            @endif
        </div>

        {{-- FORM FILTER TANGGAL --}}
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('admin.antrian.index') }}" method="GET" class="d-flex align-items-center gap-2">
                @if(request('poli'))
                    <input type="hidden" name="poli" value="{{ request('poli') }}">
                @endif

                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                    <input type="date" name="date" class="form-control border-start-0 ps-0" 
                           value="{{ $selectedDate }}" 
                           onchange="this.form.submit()" 
                           style="cursor: pointer; font-weight: 500;"
                           title="Filter Tanggal Tertentu (History)">
                </div>
                
                @if($isDateFiltered)
                    <a href="{{ route('admin.antrian.index', ['poli' => request('poli')]) }}" class="btn btn-danger shadow-sm text-white" title="Reset Filter">
                        <i class="bi bi-x-lg"></i>
                    </a>
                @endif
            </form>

            <span class="badge rounded-pill px-3 py-2 shadow-sm ms-2" style="background-color: var(--rs-green);">
                <i class="bi bi-people-fill me-1"></i> {{ $antrians->count() }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="ps-4 py-3 text-secondary small fw-bold text-center" width="10%">No Antrian</th>
                            <th class="py-3 text-secondary small fw-bold" width="20%">Nama Pasien</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="10%">Data Pasien</th>
                            <th class="py-3 text-secondary small fw-bold" width="15%">Poliklinik</th>
                            <th class="py-3 text-secondary small fw-bold" width="15%">Dokter</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="15%">Rencana Kontrol</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="10%">Status</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrians as $antrian)
                            <tr class="{{ ($antrian->status == 'Selesai' || $antrian->status == 'Batal') ? 'bg-light text-muted' : '' }}">
                                {{-- NO ANTRIAN --}}
                                <td class="ps-4 text-center">
                                    <h5 class="fw-bold mb-0" style="color: var(--rs-green); font-family: monospace;">{{ $antrian->no_antrian }}</h5>
                                </td>

                                {{-- NAMA PASIEN --}}
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-truncate" style="max-width: 200px; color: inherit;" title="{{ $antrian->nama_pasien }}">
                                            {{ $antrian->nama_pasien }}
                                        </span>
                                    </div>
                                </td>
                                
                                {{-- TOMBOL LIHAT DATA --}}
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm" onclick='showPatientDetail(@json($antrian))'>
                                        <i class="bi bi-eye-fill me-1"></i> Lihat
                                    </button>
                                </td>

                                {{-- POLIKLINIK --}}
                                <td>
                                    <span class="badge bg-white border shadow-sm fw-normal text-truncate" style="max-width: 150px; color: inherit;">
                                        {{ $antrian->poli }}
                                    </span>
                                </td>

                                {{-- DOKTER --}}
                                <td>
                                    <span class="small text-muted text-truncate-2" title="{{ $antrian->dokter }}">
                                        {{ $antrian->dokter }}
                                    </span>
                                </td>

                                {{-- RENCANA KONTROL --}}
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar-event me-2 text-muted"></i>
                                        <span class="fw-bold" style="font-size: 0.85rem; color: inherit;">
                                            {{ \Carbon\Carbon::parse($antrian->tanggal_kontrol)->locale('id')->isoFormat('D MMM Y') }}
                                        </span>
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">
                                    @if($antrian->status == 'Dipanggil')
                                        <span class="status-badge status-dipanggil"><i class="bi bi-megaphone-fill me-1"></i> Dipanggil</span>
                                    @elseif($antrian->status == 'Selesai')
                                        <span class="status-badge status-selesai"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                                    @elseif($antrian->status == 'Batal')
                                        <span class="status-badge status-batal"><i class="bi bi-x-circle-fill me-1"></i> Batal</span>
                                    @else
                                        <span class="status-badge status-menunggu"><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- Jika status belum final (bukan Selesai/Batal), tampilkan tombol aksi --}}
                                        @if(!in_array($antrian->status, ['Selesai', 'Batal']))
                                            @if($antrian->status == 'Menunggu')
                                                <form action="{{ route('admin.antrian.updateStatus', $antrian->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="Dipanggil">
                                                    <button type="submit" class="btn btn-sm btn-panggil rounded-pill px-3 shadow-sm text-nowrap" title="Panggil Pasien">
                                                        <i class="bi bi-mic-fill me-1"></i> Panggil
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('admin.antrian.updateStatus', $antrian->id) }}" method="POST" onsubmit="return confirm('Selesaikan pemeriksaan pasien ini?');">
                                                @csrf
                                                <input type="hidden" name="status" value="Selesai">
                                                <button type="submit" class="btn btn-sm btn-selesai rounded-circle shadow-sm" style="width: 32px; height: 32px; padding: 0;" title="Selesai / Sudah Diperiksa">
                                                    <i class="bi bi-check-lg fs-6"></i>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small fst-italic"><i class="bi bi-archive me-1"></i> Archived</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-3 text-muted opacity-25">
                                        <i class="bi bi-clipboard-x display-1"></i>
                                    </div>
                                    <p class="text-muted fw-bold mb-0">
                                        @if($isDateFiltered)
                                            Tidak ada data antrian pada tanggal {{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}.
                                        @else
                                            Tidak ada antrian aktif (Menunggu/Dipanggil) saat ini.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PASIEN --}}
<div class="modal fade" id="patientDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold text-dark">
                    <i class="bi bi-person-vcard text-success me-2"></i>Detail Data Pasien
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tr><td class="text-muted small fw-bold" width="35%">NIK</td><td class="fw-bold text-dark" id="modalNik"></td></tr>
                        <tr><td class="text-muted small fw-bold">Nama Pasien</td><td class="fw-bold text-dark" id="modalNama"></td></tr>
                        <tr><td class="text-muted small fw-bold">Jenis Kelamin</td><td id="modalJK"></td></tr>
                        <tr><td class="text-muted small fw-bold">Tanggal Lahir</td><td id="modalTglLahir"></td></tr>
                        <tr><td class="text-muted small fw-bold">No. HP / WA</td><td class="text-success fw-bold" id="modalHp"></td></tr>
                        <tr><td class="text-muted small fw-bold">Alamat</td><td id="modalAlamat"></td></tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function showPatientDetail(data) {
        document.getElementById('modalNik').innerText = data.nik || '-';
        document.getElementById('modalNama').innerText = data.nama_pasien || '-';
        document.getElementById('modalJK').innerText = data.jenis_kelamin || '-';
        let tglLahir = data.tanggal_lahir ? new Date(data.tanggal_lahir).toLocaleDateString('id-ID') : '-';
        document.getElementById('modalTglLahir').innerText = tglLahir;
        document.getElementById('modalHp').innerText = data.nomor_hp || '-';
        document.getElementById('modalAlamat').innerText = data.alamat || '-';
        new bootstrap.Modal(document.getElementById('patientDetailModal')).show();
    }
</script>

@endsection