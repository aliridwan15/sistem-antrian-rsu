@extends('layouts.admin')

@section('title', 'Laporan Antrian')

@section('content')

<div class="container-fluid">
    
    {{-- HEADER & FILTER --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3 no-print">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="color: var(--rs-green) !important;">Laporan Data Antrian</h4>
            <p class="text-muted small mb-0">Rekap data pasien yang telah selesai, batal, atau terlewat.</p>
        </div>

        <form action="{{ route('admin.laporan.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
            {{-- Filter Bulan --}}
            <select name="bulan" class="form-select shadow-sm" style="width: auto;" onchange="this.form.submit()">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                       {{ \Carbon\Carbon::createFromDate(null, $i, 1)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>

            {{-- Filter Tahun --}}
            <select name="tahun" class="form-select shadow-sm" style="width: auto;" onchange="this.form.submit()">
                @for($i = date('Y'); $i >= date('Y')-2; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>

            <div class="vr mx-1"></div> {{-- Pemisah Vertical --}}

            {{-- Pilihan Kertas --}}
            <select id="paperSize" class="form-select shadow-sm" style="width: auto;">
                <option value="A4">Kertas A4</option>
                <option value="F4">Kertas F4 (Folio)</option>
            </select>

            {{-- Pilihan Orientasi --}}
            <select id="orientation" class="form-select shadow-sm" style="width: auto;">
                <option value="landscape">Landscape</option>
                <option value="portrait">Portrait</option>
            </select>
            
            {{-- TOMBOL PRINT --}}
            <button type="button" onclick="printLaporan()" class="btn btn-outline-secondary shadow-sm" title="Cetak Laporan">
                <i class="bi bi-printer"></i> Cetak
            </button>
        </form>
    </div>

    {{-- AREA UTAMA (ID ini yang akan diambil isinya oleh JS) --}}
    <div id="report-area">
        
        {{-- JUDUL (Akan muncul di print karena kita sertakan di JS, tapi di hide di layar ini) --}}
        <div class="d-none d-print-block text-center mb-4" id="print-title">
            <h2 style="margin-bottom: 5px; font-weight: bold; font-family: 'Times New Roman', serif;">RSU ANNA MEDIKA MADURA</h2>
            <h4 style="margin-bottom: 5px; font-family: 'Times New Roman', serif;">Laporan Data Antrian Pasien</h4>
            <p style="margin-top: 0; font-family: 'Times New Roman', serif;">Periode: {{ \Carbon\Carbon::createFromDate(null, $bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}</p>
            <hr style="border-top: 2px solid black; opacity: 1;">
        </div>

        {{-- TABEL DATA --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 table-striped">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 small fw-bold text-center" width="5%">No</th>
                                <th class="py-3 small fw-bold">NIK</th>
                                <th class="py-3 small fw-bold">Nama Pasien</th>
                                <th class="py-3 small fw-bold">Tgl Lahir</th>
                                <th class="py-3 small fw-bold text-center">L/P</th>
                                <th class="py-3 small fw-bold">No HP</th>
                                <th class="py-3 small fw-bold">Alamat</th>
                                <th class="py-3 small fw-bold">Poli</th>
                                <th class="py-3 small fw-bold">Dokter</th>
                                <th class="py-3 small fw-bold text-center">Rencana Kontrol</th>
                                <th class="py-3 small fw-bold text-center col-status">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporans as $index => $data)
                                <tr>
                                    <td class="ps-4 text-center">{{ $index + 1 }}</td>
                                    <td class="small">{{ $data->nik }}</td>
                                    <td class="fw-bold text-dark">{{ $data->nama_pasien }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d/m/Y') }}</td>
                                    <td class="small text-center">{{ $data->jenis_kelamin == 'Laki-laki' ? 'L' : 'P' }}</td>
                                    <td class="small">{{ $data->nomor_hp }}</td>
                                    <td class="small text-truncate col-alamat" style="max-width: 150px;">{{ $data->alamat }}</td>
                                    <td><span class="badge bg-white text-dark border poli-badge">{{ $data->poli }}</span></td>
                                    <td class="small text-muted">{{ $data->dokter }}</td>
                                    <td class="text-center fw-bold text-primary date-col">
                                        {{ \Carbon\Carbon::parse($data->tanggal_kontrol)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center col-status">
                                        @if($data->status == 'Selesai') <span class="badge bg-success">Selesai</span>
                                        @elseif($data->status == 'Batal') <span class="badge bg-danger">Batal</span>
                                        @else <span class="badge bg-secondary">Terlewat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5 text-muted">Tidak ada data laporan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT CETAK DINAMIS --}}
<script>
    function printLaporan() {
        // 1. Ambil Pilihan User
        var size = document.getElementById('paperSize').value;      // A4 atau F4
        var orient = document.getElementById('orientation').value;  // portrait atau landscape
        
        // Konversi ukuran F4 ke mm (karena CSS @page size F4 tidak standar di semua browser)
        // A4 = 210mm x 297mm | F4 (Folio) = 215mm x 330mm
        var cssSize = size;
        if(size === 'F4') {
            cssSize = (orient === 'portrait') ? '215mm 330mm' : '330mm 215mm';
        } else {
            // Untuk A4, cukup tulis 'A4 landscape' atau 'A4 portrait'
            cssSize = 'A4 ' + orient;
        }

        // 2. Ambil Konten
        var titleContent = document.getElementById('print-title').innerHTML;
        var tableContent = document.querySelector('.table-responsive').innerHTML;

        // 3. Buka Jendela Baru
        var win = window.open('', '', 'height=700,width=1000');

        win.document.write('<html><head><title>Cetak Laporan</title>');
        win.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
        
        win.document.write('<style>');
        // Set Ukuran Kertas & Orientasi Dinamis
        win.document.write('@page { size: ' + cssSize + '; margin: 10mm; }');
        
        win.document.write('body { font-family: "Times New Roman", Times, serif; font-size: 10pt; background: white !important; color: black !important; -webkit-print-color-adjust: exact; padding: 20px; }');
        win.document.write('.print-header { text-align: center; margin-bottom: 20px; display: block !important; }');
        win.document.write('table { width: 100%; border-collapse: collapse; font-size: 9pt; }');
        win.document.write('th, td { border: 1px solid black !important; padding: 4px 5px !important; color: black !important; vertical-align: middle; }');
        win.document.write('th { background-color: #f0f0f0 !important; font-weight: bold; text-align: center; }');
        win.document.write('.col-status { display: none !important; }');
        win.document.write('.col-alamat { white-space: normal !important; max-width: none !important; }');
        win.document.write('.badge { border: none !important; padding: 0 !important; font-weight: normal !important; background: none !important; color: black !important; }');
        win.document.write('.text-primary, .text-muted, .text-success, .text-danger { color: black !important; }');
        win.document.write('a { text-decoration: none; color: black !important; }');
        win.document.write('</style>');
        
        win.document.write('</head><body>');
        win.document.write('<div class="print-header">' + titleContent + '</div>');
        win.document.write(tableContent);
        win.document.write('</body></html>');
        win.document.close();

        setTimeout(function() {
            win.focus();
            win.print();
            win.close();
        }, 1000);
    }
</script>

@endsection