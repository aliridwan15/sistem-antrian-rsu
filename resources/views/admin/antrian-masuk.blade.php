@extends('layouts.admin')

@section('title', 'Antrian Masuk')

@section('content')

<style>
    :root { --rs-green: #1B9C85; --rs-green-dark: #14806c; --rs-green-light: #e0f2ef; }
    .card-antrian { border: none; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
    .status-badge { padding: 6px 14px; border-radius: 50px; font-size: 0.75rem; font-weight: 600; white-space: nowrap; }
    .status-menunggu { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .status-dipanggil { background-color: var(--rs-green-light); color: var(--rs-green-dark); border: 1px solid var(--rs-green); animation: pulse-green 2s infinite; }
    .status-selesai { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
    .status-batal { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }
    .status-terlewat { background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }

    .btn-action { width: 36px; height: 36px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: none; transition: 0.3s; }
    .btn-panggil { background-color: var(--rs-green); color: white; }
    .btn-panggil:hover { background-color: var(--rs-green-dark); transform: scale(1.1); }
    .btn-selesai { background-color: #198754; color: white; }
    .btn-selesai:hover { background-color: #146c43; transform: scale(1.1); }
    .btn-batal { background-color: #dc3545; color: white; }
    .btn-batal:hover { background-color: #b02a37; transform: scale(1.1); }

    @keyframes pulse-green { 0% { box-shadow: 0 0 0 0 rgba(27, 156, 133, 0.4); } 70% { box-shadow: 0 0 0 8px rgba(27, 156, 133, 0); } 100% { box-shadow: 0 0 0 0 rgba(27, 156, 133, 0); } }
    .table th { white-space: nowrap; vertical-align: middle; } .table td { vertical-align: middle; }
    .text-truncate-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
</style>

<div class="container-fluid">
    
    {{-- HEADER & FILTER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: var(--rs-green) !important;">
                Antrian Masuk {{ request('poli') ? '- ' . request('poli') : '' }}
            </h4>
            
            @if($statusFilter == 'terlewat')
                <span class="badge bg-secondary border border-secondary shadow-sm mb-1">
                    <i class="bi bi-clock-history me-1"></i> Mode Terlewat
                </span>
                <p class="text-muted small mb-0">Menampilkan antrian yang <strong>belum selesai</strong> dari tanggal lampau.</p>
            @elseif($isDateFiltered)
                <span class="badge bg-warning text-dark border border-warning shadow-sm mb-1">
                    <i class="bi bi-funnel-fill me-1"></i> Mode History
                </span>
                <p class="text-muted small mb-0">Menampilkan data tanggal <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('d-m-Y') }}</strong>.</p>
            @else
                <span class="badge bg-success border border-success shadow-sm mb-1">
                    <i class="bi bi-broadcast me-1"></i> Mode Aktif
                </span>
                <p class="text-muted small mb-0">Menampilkan antrian aktif hari ini & masa depan.</p>
            @endif
        </div>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            <a href="{{ request()->fullUrlWithQuery(['status' => 'terlewat', 'date' => null]) }}" 
               class="btn {{ $statusFilter == 'terlewat' ? 'btn-secondary' : 'btn-outline-secondary' }} position-relative shadow-sm"
               title="Lihat Antrian Terlewat">
                <i class="bi bi-exclamation-circle-fill me-1"></i> Terlewat
                @if($countTerlewat > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                        {{ $countTerlewat }}
                    </span>
                @endif
            </a>

            <form action="{{ route('admin.antrian.index') }}" method="GET" class="d-flex align-items-center gap-2">
                @if(request('poli')) <input type="hidden" name="poli" value="{{ request('poli') }}"> @endif
                @if($statusFilter != 'terlewat')
                    <div class="input-group shadow-sm" style="width: auto;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-calendar-event"></i></span>
                        <input type="date" name="date" class="form-control border-start-0 ps-0" 
                               value="{{ $selectedDate }}" onchange="this.form.submit()" 
                               style="cursor: pointer; font-weight: 500;">
                    </div>
                @endif
                @if($isDateFiltered || $statusFilter == 'terlewat')
                    <a href="{{ route('admin.antrian.index', ['poli' => request('poli')]) }}" class="btn btn-danger shadow-sm text-white" title="Reset Filter">
                        <i class="bi bi-x-lg"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show">
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
                            <th class="ps-4 py-3 text-secondary small fw-bold text-center">No Antrian</th>
                            <th class="py-3 text-secondary small fw-bold">Nama Pasien</th>
                            <th class="py-3 text-secondary small fw-bold text-center">Data</th>
                            <th class="py-3 text-secondary small fw-bold">Poliklinik</th>
                            <th class="py-3 text-secondary small fw-bold">Dokter</th>
                            <th class="py-3 text-secondary small fw-bold text-center">Rencana Kontrol</th>
                            <th class="py-3 text-secondary small fw-bold text-center">Status</th>
                            <th class="py-3 text-secondary small fw-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrians as $antrian)
                            <tr class="{{ ($antrian->status == 'Selesai' || $antrian->status == 'Batal') ? 'bg-light text-muted' : '' }}">
                                <td class="ps-4 text-center"><h5 class="fw-bold mb-0" style="color: var(--rs-green); font-family: monospace;">{{ $antrian->no_antrian }}</h5></td>
                                <td><span class="fw-bold text-truncate" style="max-width: 200px;">{{ $antrian->nama_pasien }}</span></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-info rounded-pill px-3 shadow-sm" onclick='showPatientDetail(@json($antrian))'>
                                        <i class="bi bi-eye-fill me-1"></i> Lihat
                                    </button>
                                </td>
                                <td><span class="badge bg-white border shadow-sm fw-normal text-truncate text-dark">{{ $antrian->poli }}</span></td>
                                <td><span class="small text-muted text-truncate-2">{{ $antrian->dokter }}</span></td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="bi bi-calendar-event me-2 text-muted"></i>
                                        <span class="fw-bold {{ \Carbon\Carbon::parse($antrian->tanggal_kontrol)->isPast() ? 'text-danger' : '' }}" style="font-size: 0.85rem;">
                                            {{ \Carbon\Carbon::parse($antrian->tanggal_kontrol)->locale('id')->isoFormat('D MMM Y') }}
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($antrian->status == 'Dipanggil') <span class="status-badge status-dipanggil"><i class="bi bi-megaphone-fill me-1"></i> Dipanggil</span>
                                    @elseif($antrian->status == 'Selesai') <span class="status-badge status-selesai"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                                    @elseif($antrian->status == 'Batal') <span class="status-badge status-batal"><i class="bi bi-x-circle-fill me-1"></i> Batal</span>
                                    @else 
                                        @if(\Carbon\Carbon::parse($antrian->tanggal_kontrol)->isPast() && $statusFilter == 'terlewat')
                                            <span class="status-badge status-terlewat"><i class="bi bi-clock-history me-1"></i> Terlewat</span>
                                        @else
                                            <span class="status-badge status-menunggu"><i class="bi bi-hourglass-split me-1"></i> Menunggu</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        @if(!in_array($antrian->status, ['Selesai', 'Batal']))
                                            
                                            @if($statusFilter == 'terlewat')
                                                <form action="{{ route('admin.antrian.updateStatus', $antrian->id) }}" method="POST" onsubmit="return confirm('Batalkan antrian terlewat ini?');">
                                                    @csrf <input type="hidden" name="status" value="Batal">
                                                    <button type="submit" class="btn-action btn-batal shadow-sm" title="Batalkan"><i class="bi bi-x-lg"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.antrian.updateStatus', $antrian->id) }}" method="POST">
                                                    @csrf <input type="hidden" name="status" value="Dipanggil">
                                                    <button type="submit" class="btn-action btn-panggil shadow-sm" title="Panggil"><i class="bi bi-telephone-fill"></i></button>
                                                </form>

                                                @if($antrian->status == 'Dipanggil')
                                                    {{-- TOMBOL SELESAI -> MEMBUKA MODAL KONFIRMASI --}}
                                                    <button type="button" class="btn-action btn-selesai shadow-sm" onclick="confirmSelesai('{{ route('admin.antrian.updateStatus', $antrian->id) }}')">
                                                        <i class="bi bi-check-lg fs-5 fw-bold"></i>
                                                    </button>
                                                @endif
                                            @endif

                                        @elseif($antrian->status == 'Selesai')
                                            <span class="text-success"><i class="bi bi-check-all fs-4"></i></span>
                                        @else
                                            <span class="text-muted"><i class="bi bi-x-circle fs-5"></i></span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="mb-3 text-muted opacity-25"><i class="bi bi-clipboard-x display-1"></i></div>
                                    <p class="text-muted fw-bold mb-0">
                                        @if($statusFilter == 'terlewat') Tidak ada data antrian terlewat.
                                        @elseif($isDateFiltered) Tidak ada data pada tanggal tersebut.
                                        @else Tidak ada antrian aktif saat ini. @endif
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
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-person-vcard text-success me-2"></i>Detail Data Pasien</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <table class="table table-borderless mb-0">
                    <tr><td class="text-muted small fw-bold" width="35%">NIK</td><td class="fw-bold text-dark" id="modalNik"></td></tr>
                    <tr><td class="text-muted small fw-bold">Nama Pasien</td><td class="fw-bold text-dark" id="modalNama"></td></tr>
                    <tr><td class="text-muted small fw-bold">Jenis Kelamin</td><td id="modalJK"></td></tr>
                    <tr><td class="text-muted small fw-bold">Tanggal Lahir</td><td id="modalTglLahir"></td></tr>
                    <tr><td class="text-muted small fw-bold">No. HP / WA</td><td class="text-success fw-bold" id="modalHp"></td></tr>
                    <tr><td class="text-muted small fw-bold">Alamat</td><td id="modalAlamat"></td></tr>
                </table>
            </div>
            <div class="modal-footer border-top-0 bg-light"><button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button></div>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI SELESAI --}}
<div class="modal fade" id="modalKonfirmasiSelesai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-success opacity-75">
                    <i class="bi bi-check-circle-fill display-1"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark">Konfirmasi Selesai</h5>
                <p class="text-muted small mb-4">
                    Apakah Anda yakin ingin menyelesaikan pemeriksaan pasien ini? Antrian akan ditandai sebagai <strong>Selesai</strong>.
                </p>
                <form id="formSelesai" method="POST" action="">
                    @csrf
                    <input type="hidden" name="status" value="Selesai">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success rounded-pill fw-bold">Ya, Selesaikan</button>
                        <button type="button" class="btn btn-light rounded-pill text-muted" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
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

    // FUNGSI BUKA MODAL KONFIRMASI SELESAI
    function confirmSelesai(url) {
        // Set action form di dalam modal sesuai ID antrian
        document.getElementById('formSelesai').action = url;
        // Tampilkan modal
        new bootstrap.Modal(document.getElementById('modalKonfirmasiSelesai')).show();
    }
</script>

@endsection