<!DOCTYPE html>
<html>
<head>
    <title>Tiket Antrian - {{ $antrian->no_antrian }}</title>
    <style>
        /* Reset & Basic Style */
        @page { margin: 0px; }
        body { 
            font-family: sans-serif; 
            margin: 0; 
            padding: 0; 
            color: #333;
            background-color: #fff;
        }

        /* Container Utama (Mirip Card) */
        .container {
            padding: 0 20px 20px 20px;
            text-align: center;
        }

        /* Header Bar (Garis Hijau di Atas) */
        .header-bar {
            width: 100%;
            height: 12px;
            background-color: #1B9C85; /* Warna Hijau RS */
            margin-bottom: 20px;
        }

        /* Logo & Judul */
        .logo {
            width: 50px;
            height: auto;
            margin-bottom: 5px;
        }
        .rs-name {
            font-size: 16px;
            font-weight: bold;
            margin: 5px 0 0 0;
            text-transform: uppercase;
            color: #000;
        }
        .subtitle {
            font-size: 10px;
            color: #6c757d;
            margin-top: 2px;
        }

        /* Garis Putus-putus */
        .dashed-line {
            border-top: 2px dashed #d1d5db;
            margin: 15px 0;
            width: 100%;
        }

        /* Nomor Antrian */
        .label-nomor {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .nomor-besar {
            font-family: 'Courier New', Courier, monospace; /* Font Monospace */
            font-size: 48px;
            font-weight: 800;
            color: #1B9C85;
            line-height: 1;
            margin: 5px 0 15px 0;
        }

        /* Badge Poli */
        .badge-poli {
            display: inline-block;
            padding: 6px 15px;
            border: 1px solid #6c757d;
            border-radius: 50px; /* Membuat lonjong */
            font-size: 12px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }

        /* Tabel Informasi */
        .info-table {
            width: 100%;
            font-size: 12px;
            text-align: left;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .label-td {
            color: #6c757d;
            width: 60px; /* Lebar kolom label */
        }
        .val-td {
            font-weight: bold;
            color: #000;
            text-align: right;
        }

        /* Kotak Himbauan (Kuning) */
        .notice-box {
            background-color: #fff3cd;
            border: 1px solid #ffecb5;
            color: #664d03;
            padding: 10px;
            border-radius: 8px;
            font-size: 9px;
            font-style: italic;
            text-align: center;
            margin-top: 10px;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #aaa;
            font-style: italic;
        }
    </style>
</head>
<body>

    {{-- Garis Header Hijau --}}
    <div class="header-bar"></div>

    <div class="container">
        
        {{-- Logo & Judul --}}
        {{-- Pastikan path image benar. Untuk DomPDF gunakan public_path() --}}
        <img src="{{ public_path('images/logors.png') }}" class="logo" alt="Logo">
        <h3 class="rs-name">RSU Anna Medika</h3>
        <div class="subtitle">Bukti Pendaftaran Online</div>

        {{-- Garis Pemisah --}}
        <div class="dashed-line"></div>

        {{-- Nomor Antrian --}}
        <div class="label-nomor">Nomor Antrian</div>
        <div class="nomor-besar">{{ $antrian->no_antrian }}</div>
        
        {{-- Badge Poli --}}
        <div class="badge-poli">
            {{ $antrian->poli }}
        </div>

        {{-- Tabel Info (Tanpa Status) --}}
        <table class="info-table">
            <tr>
                <td class="label-td">Pasien</td>
                <td class="val-td">{{ $antrian->nama_pasien }}</td>
            </tr>
            <tr>
                <td class="label-td">Dokter</td>
                <td class="val-td">{{ $antrian->dokter }}</td>
            </tr>
            <tr>
                <td class="label-td">Tgl</td>
                <td class="val-td">{{ \Carbon\Carbon::parse($antrian->tanggal_kontrol)->format('d-m-Y') }}</td>
            </tr>
        </table>

        {{-- Pesan Himbauan (Background Kuning) --}}
        <div class="notice-box">
            Mohon hadir 30 menit sebelum jadwal praktik dokter untuk verifikasi data.
        </div>

        {{-- Footer --}}
        <div class="footer">
            "Terima Kasih Atas Kunjungan Anda"<br>
            Dicetak otomatis: {{ date('d-m-Y H:i') }}
        </div>

    </div>
</body>
</html>