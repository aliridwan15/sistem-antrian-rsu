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

        <form action="{{ route('admin.laporan.index') }}" method="GET" class="d-flex gap-2">
            <select name="bulan" class="form-select shadow-sm" onchange="this.form.submit()">
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ sprintf('%02d', $i) }}" {{ $bulan == sprintf('%02d', $i) ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromDate(null, $i)->locale('id')->isoFormat('MMMM') }}
                    </option>
                @endfor
            </select>
            <select name="tahun" class="form-select shadow-sm" onchange="this.form.submit()">
                @for($i = date('Y'); $i >= date('Y')-2; $i--)
                    <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary shadow-sm" title="Cetak Laporan">
                <i class="bi bi-printer"></i>
            </button>
        </form>
    </div>

    {{-- JUDUL KHUSUS PRINT --}}
    <div class="only-print text-center mb-4">
        <h3>Laporan Data Antrian Pasien</h3>
        <p>Periode: {{ \Carbon\Carbon::createFromDate(null, $bulan)->locale('id')->isoFormat('MMMM') }} {{ $tahun }}</p>
        <hr>
    </div>

    {{-- TABEL LAPORAN --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden print-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-striped table-print">
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
                            {{-- KOLOM STATUS: HILANG SAAT PRINT --}}
                            <th class="py-3 small fw-bold text-center no-print-col">Status</th>
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
                                <td class="small text-truncate address-col" style="max-width: 150px;" title="{{ $data->alamat }}">
                                    {{ $data->alamat }}
                                </td>
                                <td><span class="badge bg-white text-dark border poli-badge">{{ $data->poli }}</span></td>
                                <td class="small text-muted">{{ $data->dokter }}</td>
                                <td class="text-center fw-bold text-primary date-col">
                                    {{ \Carbon\Carbon::parse($data->tanggal_kontrol)->format('d/m/Y') }}
                                </td>
                                
                                {{-- KOLOM DATA STATUS: HILANG SAAT PRINT --}}
                                <td class="text-center no-print-col">
                                    @if($data->status == 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($data->status == 'Batal')
                                        <span class="badge bg-danger">Batal</span>
                                    @else
                                        <span class="badge bg-secondary">Terlewat</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    Tidak ada data laporan untuk periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS GLOBAL */
    .only-print { display: none; }

    /* CSS KHUSUS PRINT */
    @media print {
        @page {
            size: landscape; /* Orientasi Landscape agar muat banyak kolom */
            margin: 10mm;
        }

        body {
            background-color: white !important;
            font-family: 'Times New Roman', Times, serif; /* Font formal */
            font-size: 10pt; /* Ukuran font lebih kecil agar muat */
            color: black !important;
        }

        /* Sembunyikan elemen yang tidak perlu */
        /* UPDATE: Tambahkan .no-print-col untuk menyembunyikan kolom status */
        .no-print, .sidebar, .mobile-toggle, .btn, .no-print-col {
            display: none !important;
        }

        /* Tampilkan elemen khusus print */
        .only-print { display: block; }

        /* Atur layout utama */
        .main-content {
            margin-left: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        .container-fluid {
            padding: 0 !important;
            max-width: 100% !important;
        }

        /* Atur Tabel */
        .print-card {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }

        .table-print {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9pt; /* Font tabel lebih kecil */
        }

        .table-print th, .table-print td {
            border: 1px solid #000 !important; /* Border hitam tegas */
            padding: 4px 6px !important; /* Padding lebih rapat */
            color: #000 !important;
            vertical-align: middle;
        }

        .table-print th {
            background-color: #f0f0f0 !important;
            font-weight: bold;
            text-align: center;
        }

        /* Hapus style truncate/potongan teks saat print */
        .address-col, .text-truncate {
            white-space: normal !important;
            max-width: none !important;
            overflow: visible !important;
            text-overflow: clip !important;
        }

        /* Hapus warna badge, jadikan teks biasa */
        .badge {
            background: none !important;
            color: #000 !important;
            border: none !important;
            padding: 0 !important;
            font-weight: normal !important;
        }
        
        .date-col { color: black !important; }
        
        /* Pastikan link tidak dicetak warnanya */
        a { text-decoration: none; color: black !important; }
    }
</style>

@endsection