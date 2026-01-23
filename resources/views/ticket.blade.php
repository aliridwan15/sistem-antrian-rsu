@extends('layouts.app')

@section('title', 'Tiket Antrian Saya - RSU Anna Medika')

@section('content')

<style>
    /* CSS Tampilan Web */
    .ticket-container { padding: 40px 20px; padding-bottom: 100px; }

    .ticket-card {
        background: white; border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: 1px solid #e0e0e0; 
        overflow: hidden;
        position: relative;
        max-width: 350px; margin: 0 auto; 
        transition: transform 0.2s;
    }
    
    @media screen {
        .ticket-card:hover { transform: translateY(-5px); }
    }

    .ticket-header { 
        height: 10px; background: linear-gradient(to right, #1B9C85, #14806c); 
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    
    .dashed-line {
        border-top: 3px dashed #d1d5db; margin: 20px 0; position: relative; width: 100%;
    }
    .dashed-line::before, .dashed-line::after {
        content: ""; position: absolute; width: 24px; height: 24px;
        background-color: #f5f5f5; border-radius: 50%; top: -14px; z-index: 10;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    /* Trik agar bulatan saat print warnanya putih */
    @media print {
        .dashed-line::before, .dashed-line::after { background-color: white !important; }
    }

    .dashed-line::before { left: -12px; }
    .dashed-line::after { right: -12px; }

    .ticket-content { padding: 25px; text-align: center; }
    
    .nomor-antrian {
        font-size: 3.5rem; font-weight: 800; color: #1B9C85;
        font-family: 'Courier New', monospace; line-height: 1; margin: 10px 0;
        -webkit-print-color-adjust: exact; print-color-adjust: exact;
    }
    
    .info-table { width: 100%; margin-top: 20px; text-align: left; font-size: 0.9rem; }
    .info-table td { padding: 4px 0; vertical-align: top; }
    .info-table .val { font-weight: 600; text-align: right; }

    .ticket-actions { background: #f8f9fa; padding: 15px; border-top: 1px solid #eee; display: flex; gap: 10px; }
    
    /* Hilangkan outline saat klik */
    .btn:focus, .btn:active { outline: none !important; box-shadow: none !important; }

    /* Iframe tersembunyi */
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
        @foreach($antrians as $antrian)
            <div class="col-md-6 col-lg-4">
                {{-- ID untuk mengambil konten --}}
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
                    <div class="ticket-actions">
                        <button type="button" class="btn btn-outline-secondary btn-sm flex-fill rounded-pill" onclick="showDetail('{{ $antrian->id }}')">
                            <i class="bi bi-eye"></i> Detail
                        </button>
                        <button type="button" class="btn btn-rs btn-sm flex-fill rounded-pill" onclick="printTicket(this, '{{ $antrian->id }}')">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
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

        // Visual Feedback (Opsional, biar user tau sedang proses)
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Loading...';
        btn.disabled = true;

        // 1. Hapus iframe lama (Garbage Collection Manual agar memori tidak penuh)
        let oldIframe = document.getElementById('printing-frame');
        if (oldIframe) document.body.removeChild(oldIframe);
        
        // 2. Buat Iframe Baru
        let iframe = document.createElement('iframe');
        iframe.id = 'printing-frame';
        document.body.appendChild(iframe);

        const doc = iframe.contentWindow.document;
        
        // 3. Clone Konten Tiket
        const content = source.cloneNode(true);
        const actions = content.querySelector('.ticket-actions');
        if(actions) actions.remove(); 

        // 4. INJECT HTML & CSS (Gunakan margin: 0 auto untuk centering yang aman)
        doc.open();
        doc.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Cetak Tiket</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    /* Reset Body */
                    body {
                        margin: 0; padding: 20px 0;
                        background-color: white;
                        -webkit-print-color-adjust: exact !important; 
                        print-color-adjust: exact !important;
                    }
                    
                    /* Wrapper untuk centering */
                    .print-wrapper {
                        width: 100%;
                        text-align: center; /* Fallback centering */
                    }

                    /* Style Card */
                    .ticket-card {
                        background: white; 
                        border-radius: 16px;
                        border: 1px solid #e0e0e0; 
                        overflow: hidden;
                        position: relative;
                        width: 350px; /* Lebar Tetap */
                        
                        /* Centering Technique yang Paling Aman untuk Print */
                        margin: 0 auto; 
                        display: inline-block; /* Agar text-align center bekerja */
                        text-align: left; /* Reset text align di dalam kartu */
                        
                        box-shadow: none;
                    }

                    /* Paksa Header Hijau Muncul */
                    .ticket-header { 
                        height: 10px; 
                        background: linear-gradient(to right, #1B9C85, #14806c) !important;
                        -webkit-print-color-adjust: exact !important;
                    }
                    
                    /* Style Elemen Lain (Sama Persis UI) */
                    .dashed-line { border-top: 3px dashed #d1d5db; margin: 20px 0; position: relative; width: 100%; }
                    .dashed-line::before, .dashed-line::after {
                        content: ""; position: absolute; width: 24px; height: 24px;
                        background-color: white; /* Putih */
                        border-radius: 50%; top: -14px; z-index: 10;
                    }
                    .dashed-line::before { left: -12px; }
                    .dashed-line::after { right: -12px; }

                    .ticket-content { padding: 25px; text-align: center; }
                    .nomor-antrian { font-size: 3.5rem; font-weight: 800; color: #1B9C85 !important; font-family: 'Courier New', monospace; line-height: 1; margin: 10px 0; }
                    .info-table { width: 100%; margin-top: 20px; text-align: left; font-size: 0.9rem; }
                    .info-table td { padding: 4px 0; vertical-align: top; }
                    .info-table .val { font-weight: 600; text-align: right; }
                    
                    .text-success { color: #198754 !important; }
                    .text-muted { color: #6c757d !important; }
                    .text-secondary { color: #6c757d !important; }
                    .badge { border: 1px solid #6c757d; color: #000 !important; font-weight: normal; }
                </style>
            </head>
            <body>
                <div class="print-wrapper">
                    ${content.outerHTML}
                </div>
            </body>
            </html>
        `);
        doc.close();

        // 5. Print dengan Timeout (Penting untuk Anti-Hang)
        iframe.onload = function() {
            setTimeout(() => {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
                
                // Reset Tombol
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 500); 
        };
    }
</script>

@endsection