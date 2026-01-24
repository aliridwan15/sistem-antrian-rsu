@extends('layouts.app')

@section('title', 'Tiket Antrian Saya - RSU Anna Medika')

@section('content')

<style>
    /* --- BACKGROUND HALAMAN WEB --- */
    body::before {
        content: ""; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background-image: url('{{ asset('images/rsanna.jpg') }}');
        background-position: center; background-size: cover; background-repeat: no-repeat;
        opacity: 0.15; z-index: -1; filter: grayscale(100%);
    }

    /* CSS Container */
    .ticket-container { padding: 40px 20px; padding-bottom: 100px; }

    /* CSS Card Tiket */
    .ticket-card {
        background: white; border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #e0e0e0; 
        overflow: hidden; position: relative;
        max-width: 350px; margin: 0 auto; 
        transition: transform 0.2s;
        z-index: 1;
    }
    
    @media screen {
        .ticket-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.15); }
    }

    /* CSS Placeholder */
    .empty-ticket-card {
        background: rgba(255, 255, 255, 0.85); 
        border: 2px dashed #adb5bd; 
        border-radius: 16px;
        padding: 40px 30px;
        text-align: center;
        max-width: 400px; margin: 0 auto;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        min-height: 350px;
        transition: all 0.3s ease;
    }
    .empty-ticket-card:hover { background: white; border-color: #1B9C85; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

    .ticket-header { height: 10px; background: linear-gradient(to right, #1B9C85, #14806c); }
    
    .dashed-line { border-top: 3px dashed #d1d5db; margin: 20px 0; position: relative; width: 100%; }
    .dashed-line::before, .dashed-line::after {
        content: ""; position: absolute; width: 24px; height: 24px;
        background-color: white; border-radius: 50%; top: -14px; z-index: 10;
        border: 1px solid #f0f0f0;
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

    /* Action Buttons: Grid layout agar rapi */
    .ticket-actions { 
        background: #f8f9fa; padding: 15px; border-top: 1px solid #eee; 
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px; 
    }
    
    /* Tombol Hapus Full Width di bawah */
    .btn-delete-ticket { grid-column: span 2; margin-top: 5px; }

    .btn:focus, .btn:active { outline: none !important; box-shadow: none !important; }
    #printing-frame { position: fixed; top: -10000px; left: -10000px; width: 0; height: 0; }
</style>

<div class="container ticket-container">
    @if(session('success'))
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-4 text-center mx-auto shadow-sm" style="max-width: 600px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="text-center mb-5">
        <h3 class="fw-bold text-dark">Antrian Aktif Anda</h3>
        <p class="text-muted">Berikut adalah daftar tiket antrian yang Anda miliki hari ini.</p>
    </div>

    <div class="row g-4 justify-content-center">
        @forelse($antrians as $antrian)
            {{-- KARTU TIKET --}}
            <div class="col-md-6 col-lg-4">
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
                            <tr><td class="text-muted">Pasien</td><td class="val text-truncate" style="max-width: 140px;">{{ $antrian->nama_pasien }}</td></tr>
                            <tr><td class="text-muted">Dokter</td><td class="val text-truncate" style="max-width: 140px;">{{ $antrian->dokter }}</td></tr>
                            <tr><td class="text-muted">Tgl</td><td class="val">{{ \Carbon\Carbon::parse($antrian->tanggal_kontrol)->format('d-m-Y') }}</td></tr>
                            <tr><td class="text-muted">Status</td><td class="val text-success">{{ $antrian->status }}</td></tr>
                        </table>
                        <p class="fst-italic text-muted mt-4 mb-0" style="font-size: 0.75rem;">"Terima Kasih Atas Kunjungan Anda"</p>
                        <small class="text-muted d-none d-print-block mt-1" style="font-size: 0.65rem;">Dicetak: {{ date('d-m-Y H:i') }}</small>
                    </div>
                    
                    {{-- TOMBOL AKSI --}}
                    <div class="ticket-actions">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill" onclick="showDetail('{{ $antrian->id }}')">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                        <button type="button" class="btn btn-rs btn-sm rounded-pill" onclick="printTicket(this, '{{ $antrian->id }}')">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                        
                        {{-- TOMBOL HAPUS (DENGAN MODAL) --}}
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill btn-delete-ticket" 
                                onclick="confirmDelete('{{ $antrian->id }}', '{{ $antrian->no_antrian }}')">
                            <i class="bi bi-trash"></i> Batalkan / Hapus
                        </button>
                    </div>
                </div>
            </div>
        @empty
            {{-- PLACEHOLDER KOSONG --}}
            <div class="col-12 text-center">
                <div class="empty-ticket-card">
                    <div class="mb-4 text-secondary opacity-25">
                        <i class="bi bi-ticket-perforated display-1"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Belum Memiliki Antrian</h5>
                    <p class="text-muted mb-4 px-md-4">
                        Mohon maaf, Anda belum terdaftar dalam antrian poliklinik untuk hari ini. Silakan melakukan pendaftaran terlebih dahulu melalui menu utama.
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-rs rounded-pill px-5 py-2 shadow-sm fw-bold">
                        <i class="bi bi-pencil-square me-2"></i>Daftar Antrian Sekarang
                    </a>
                </div>
            </div>
        @endforelse
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

{{-- MODAL KONFIRMASI HAPUS --}}
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-4">
                <div class="mb-3 text-danger opacity-75">
                    <i class="bi bi-exclamation-circle display-1"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark">Batalkan Antrian?</h5>
                <p class="text-muted small mb-4">
                    Apakah Anda yakin ingin membatalkan antrian <strong id="deleteTicketNo" class="text-dark"></strong>? Tindakan ini tidak dapat dibatalkan.
                </p>
                <form id="deleteForm" action="" method="POST" class="d-grid gap-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill fw-bold">Ya, Batalkan</button>
                    <button type="button" class="btn btn-light rounded-pill text-muted" data-bs-dismiss="modal">Tidak, Kembali</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // --- FUNGSI MODAL HAPUS ---
    function confirmDelete(id, noAntrian) {
        // Set Action URL Form
        // Pastikan route destroy sudah dibuat: Route::delete('/antrian/{id}', [HomeController::class, 'destroy'])->name('antrian.destroy');
        let url = "{{ route('antrian.destroy', ':id') }}";
        url = url.replace(':id', id);
        
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteTicketNo').innerText = noAntrian;
        
        new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
    }

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

    function printTicket(btn, id) {
        const source = document.getElementById('ticket-' + id);
        if (!source) return;

        const originalText = btn.innerHTML;
        btn.innerHTML = 'Loading...';
        btn.disabled = true;

        let oldIframe = document.getElementById('printing-frame');
        if (oldIframe) document.body.removeChild(oldIframe);
        
        let iframe = document.createElement('iframe');
        iframe.id = 'printing-frame';
        document.body.appendChild(iframe);

        const doc = iframe.contentWindow.document;
        
        const content = source.cloneNode(true);
        const actions = content.querySelector('.ticket-actions');
        if(actions) actions.remove(); 

        doc.open();
        doc.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Tiket</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { margin: 0; padding: 20px 0; background-color: white; font-family: sans-serif; }
                    .print-wrapper { width: 100%; text-align: center; }
                    .ticket-card {
                        background: white; border-radius: 16px; border: 2px solid #000;
                        overflow: hidden; position: relative; width: 350px; margin: 0 auto;
                        display: inline-block; text-align: left; box-shadow: none;
                    }
                    .ticket-header { 
                        height: 10px; background: linear-gradient(to right, #1B9C85, #14806c) !important;
                        -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important;
                    }
                    .dashed-line { display: none; } 
                    .ticket-content { padding: 25px; text-align: center; }
                    .nomor-antrian { 
                        font-size: 3.5rem; font-weight: 800; color: #000 !important; 
                        font-family: 'Courier New', monospace; line-height: 1; margin: 10px 0; 
                    }
                    .info-table { width: 100%; margin-top: 20px; text-align: left; font-size: 0.9rem; color: #000; }
                    .info-table td { padding: 5px 0; vertical-align: top; border-bottom: 1px dashed #999; }
                    .info-table .val { font-weight: 600; text-align: right; }
                    .text-success { color: #000 !important; font-weight: bold; text-transform: uppercase; }
                    .text-muted { color: #000 !important; }
                    .text-secondary { color: #000 !important; }
                    .badge { border: 1px solid #000; color: #000 !important; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="print-wrapper">${content.outerHTML}</div>
            </body>
            </html>
        `);
        doc.close();

        iframe.onload = function() {
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 500); 
        };
    }
</script>

@endsection