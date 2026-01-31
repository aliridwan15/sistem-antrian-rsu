@extends('layouts.app')

@section('title', 'Cari Tiket Antrian - RSU Anna Medika')

@section('content')

<style>
    body::before {
        content: ""; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background-image: url('{{ asset('images/rsanna.jpg') }}');
        background-position: center; background-size: cover; background-repeat: no-repeat;
        opacity: 0.15; z-index: -1; filter: grayscale(100%);
    }
    .ticket-container { padding: 40px 20px; padding-bottom: 100px; }
    .ticket-card {
        background: white; border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #e0e0e0; 
        overflow: hidden; position: relative;
        max-width: 350px; margin: 0 auto; 
        transition: transform 0.2s;
        z-index: 1;
    }
    .ticket-header { height: 10px; background: linear-gradient(to right, #1B9C85, #14806c); }
    .dashed-line { border-top: 3px dashed #d1d5db; margin: 20px 0; position: relative; width: 100%; }
    .dashed-line::before, .dashed-line::after {
        content: ""; position: absolute; width: 24px; height: 24px;
        background-color: white; border-radius: 50%; top: -14px; z-index: 10; border: 1px solid #f0f0f0;
    }
    .dashed-line::before { left: -12px; border-left: 0; }
    .dashed-line::after { right: -12px; border-right: 0; }
    .ticket-content { padding: 25px; text-align: center; }
    .nomor-antrian {
        font-size: 3.5rem; font-weight: 800; color: #1B9C85;
        font-family: 'Courier New', monospace; line-height: 1; margin: 10px 0;
    }
    .info-table { width: 100%; margin-top: 20px; text-align: left; font-size: 0.9rem; }
    .info-table td { padding: 4px 0; vertical-align: top; }
    .info-table .val { font-weight: 600; text-align: right; }
    .ticket-actions { 
        background: #f8f9fa; padding: 15px; border-top: 1px solid #eee; 
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px; 
    }
    .filter-box {
        background: rgba(255, 255, 255, 0.95); border-radius: 12px; padding: 20px;
        margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #eee;
    }
    /* Animasi Fade In */
    .fade-in { animation: fadeIn 0.5s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="container ticket-container">
    <div class="text-center mb-4">
        {{-- JUDUL DIPERBAIKI --}}
        <h3 class="fw-bold text-dark">Cari Tiket Antrian</h3>
        <p class="text-muted">Cari tiket antrian Anda (Tanpa Login)</p>
    </div>

    {{-- FILTER SECTION --}}
    @if($antrians->isNotEmpty())
    <div class="filter-box mx-auto" style="max-width: 800px;">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-muted fw-bold">Cari Nama Pasien</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="filterNama" class="form-control border-start-0" placeholder="Ketik nama untuk mencari...">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted fw-bold">Filter Poli</label>
                <select id="filterPoli" class="form-select">
                    <option value="">Semua Poli</option>
                    @foreach($antrians->pluck('poli')->unique()->sort() as $p)
                        <option value="{{ $p }}">{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted fw-bold">Filter Dokter</label>
                <select id="filterDokter" class="form-select" disabled>
                    <option value="">Semua Dokter</option>
                </select>
            </div>
        </div>
        <div class="text-center mt-3">
            <small class="text-muted fst-italic">*Hasil pencarian akan muncul otomatis saat Anda mengetik</small>
        </div>
    </div>
    @endif

    {{-- DAFTAR TIKET (DEFAULT HIDDEN: d-none) --}}
    <div class="row g-4 justify-content-center d-none fade-in" id="ticketList">
        @foreach($antrians as $antrian)
            <div class="col-md-6 col-lg-4 ticket-item" 
                 data-nama="{{ strtolower($antrian->nama_pasien) }}" 
                 data-poli="{{ $antrian->poli }}" 
                 data-dokter="{{ $antrian->dokter }}">
                 
                <div class="ticket-card h-100" id="ticket-{{ $antrian->id }}">
                    <div class="ticket-header"></div>
                    <div class="ticket-content">
                        <div class="mb-3">
                            <img src="{{ asset('images/logors.png') }}" alt="Logo" width="45" class="mb-2">
                            <h6 class="fw-bold mb-0 text-dark">RSU ANNA MEDIKA</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">Bukti Pendaftaran Online</small>
                        </div>
                        <div class="dashed-line"></div>
                        <p class="text-uppercase fw-bold text-secondary small mb-0 mt-3">Nomor Antrian</p>
                        <div class="nomor-antrian">{{ $antrian->no_antrian }}</div>
                        <span class="badge bg-white text-dark border border-secondary px-3 py-1 rounded-pill mb-3">
                            {{ $antrian->poli }}
                        </span>
                        
                        <table class="info-table">
                            <tr>
                                <td class="text-muted">Pasien</td>
                                <td class="val text-wrap">{{ $antrian->nama_pasien }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Dokter</td>
                                <td class="val text-wrap">{{ $antrian->dokter }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Tgl</td>
                                <td class="val">{{ \Carbon\Carbon::parse($antrian->tanggal_kontrol)->format('d-m-Y') }}</td>
                            </tr>
                        </table>

                        <div class="mt-3 p-2 bg-warning bg-opacity-10 rounded border border-warning border-opacity-25">
                            <p class="mb-0 small text-muted fst-italic" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle me-1"></i>
                                Mohon hadir 30 menit sebelum jadwal praktik.
                            </p>
                        </div>
                    </div>
                    
                    <div class="ticket-actions">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="showDetail('{{ $antrian->id }}')">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                        <a href="{{ route('tiket.download', $antrian->id) }}" class="btn btn-rs btn-sm rounded-pill">
                            <i class="bi bi-download"></i> Unduh PDF
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    {{-- PESAN JIKA TIDAK DITEMUKAN --}}
    <div id="noResult" class="text-center py-5 d-none">
        <div class="mb-3 text-muted opacity-50"><i class="bi bi-search display-1"></i></div>
        <h5 class="text-muted fw-bold">Data tidak ditemukan</h5>
        <p class="text-muted small">Coba kata kunci lain atau pastikan nama sudah benar.</p>
    </div>

    {{-- PESAN DEFAULT (SAAT BELUM CARI) --}}
    <div id="startSearchMessage" class="text-center py-5">
        <div class="mb-3 text-muted opacity-25"><i class="bi bi-person-badge display-1"></i></div>
        <h5 class="text-muted fw-bold">Silakan Cari Nama Pasien</h5>
        <p class="text-muted small">Ketik nama pasien di kolom pencarian untuk melihat hasil tiket.</p>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div class="modal fade" id="universalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-transparent shadow-none">
            <div id="modal-content-area" class="shadow-lg rounded-4 overflow-hidden"></div>
            <div class="text-center mt-3">
                <button type="button" class="btn btn-light rounded-circle shadow" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const filterNama = document.getElementById('filterNama');
        const filterPoli = document.getElementById('filterPoli');
        const filterDokter = document.getElementById('filterDokter');
        const ticketList = document.getElementById('ticketList'); // Container Utama
        const ticketItems = document.querySelectorAll('.ticket-item');
        const noResult = document.getElementById('noResult');
        const startMsg = document.getElementById('startSearchMessage');

        let poliDokterMap = {};
        let allDoctors = new Set(); 

        // 1. DATA PREPARATION (Ambil data dari HTML hidden elements)
        ticketItems.forEach(item => {
            let p = item.getAttribute('data-poli');
            let d = item.getAttribute('data-dokter');
            if (!poliDokterMap[p]) poliDokterMap[p] = new Set();
            poliDokterMap[p].add(d);
            allDoctors.add(d);
        });

        if (allDoctors.size > 0) populateDokter(allDoctors);

        function populateDokter(doctorSet) {
            let currentSelected = filterDokter.value;
            filterDokter.innerHTML = '<option value="">Semua Dokter</option>';
            Array.from(doctorSet).sort().forEach(doc => {
                let opt = document.createElement('option');
                opt.value = doc;
                opt.textContent = doc;
                filterDokter.appendChild(opt);
            });
            filterDokter.disabled = false;
            if (doctorSet.has(currentSelected)) filterDokter.value = currentSelected;
            else filterDokter.value = "";
        }

        if(filterPoli) {
            filterPoli.addEventListener('change', function() {
                const selectedPoli = this.value;
                if (selectedPoli === "") populateDokter(allDoctors);
                else {
                    let doctorsInPoli = poliDokterMap[selectedPoli] || new Set();
                    populateDokter(doctorsInPoli);
                }
                filterTickets();
            });
        }

        // 2. LOGIC FILTER UTAMA (Sembunyikan/Tampilkan Tiket)
        function filterTickets() {
            const namaVal = filterNama.value.toLowerCase().trim(); // Trim spasi
            const poliVal = filterPoli.value;
            const dokterVal = filterDokter.value;
            
            // JIKA BELUM ADA INPUT NAMA (Minimal 3 karakter biar gak spam)
            // Logic: Kalau nama < 3 karakter, sembunyikan semua tiket.
            if (namaVal.length < 3) {
                ticketList.classList.add('d-none'); // Sembunyikan hasil
                startMsg.classList.remove('d-none'); // Tampilkan pesan "Silakan Cari"
                noResult.classList.add('d-none');
                return; 
            }

            // JIKA SUDAH ADA INPUT >= 3 Karakter -> TAMPILKAN CONTAINER HASIL
            ticketList.classList.remove('d-none');
            startMsg.classList.add('d-none');

            let visibleCount = 0;

            ticketItems.forEach(item => {
                const nama = item.getAttribute('data-nama');
                const poli = item.getAttribute('data-poli');
                const dokter = item.getAttribute('data-dokter');

                const matchNama = nama.includes(namaVal);
                const matchPoli = poliVal === "" || poli === poliVal;
                const matchDokter = dokterVal === "" || dokter === dokterVal;

                if (matchNama && matchPoli && matchDokter) {
                    item.style.display = ""; // Tampilkan card tiket ini
                    visibleCount++;
                } else {
                    item.style.display = "none"; // Sembunyikan card tiket ini
                }
            });

            // Handle No Result
            if (visibleCount === 0) {
                noResult.classList.remove('d-none');
            } else {
                noResult.classList.add('d-none');
            }
        }

        if(filterNama) filterNama.addEventListener('input', filterTickets);
        if(filterDokter) filterDokter.addEventListener('change', filterTickets);
    });

    function showDetail(id) {
        const source = document.getElementById('ticket-' + id);
        if (!source) return;
        const clone = source.cloneNode(true);
        const actions = clone.querySelector('.ticket-actions');
        if(actions) actions.remove();
        const modalArea = document.getElementById('modal-content-area');
        modalArea.innerHTML = ''; 
        modalArea.appendChild(clone);
        new bootstrap.Modal(document.getElementById('universalModal')).show();
    }
</script>

@endsection