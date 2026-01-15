@extends('layouts.app')

@section('title', 'Beranda - RSU Anna Medika Madura')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>

<style>
    /* CSS Styles (Sama seperti sebelumnya) */
    :root { --rs-green: #1B9C85; --rs-green-light: #e0f2ef; --adp-background-color-selected: var(--rs-green); }
    .air-datepicker-global-container { z-index: 999999 !important; }
    .btn-rs { background-color: var(--rs-green); border-color: var(--rs-green); color: white; }
    .btn-rs:hover { background-color: #14806c; border-color: #14806c; color: white; }
    .btn-outline-rs { color: var(--rs-green); border-color: var(--rs-green); background: transparent; }
    .btn-outline-rs:hover { background-color: var(--rs-green); color: white; }
    .air-datepicker-button { color: var(--rs-green); font-weight: bold; }
    .input-group-text { background-color: white; border-left: 0; cursor: pointer; }
    .bg-white-force { background-color: white !important; cursor: pointer; border-right: 0; }
    .alert-pengantar { background-color: #f8fffe; border: 1px solid #cbf0ea; border-left: 5px solid var(--rs-green); }
    
    @media print {
        body * { visibility: hidden; }
        #modalTiketContent, #modalTiketContent * { visibility: visible; }
        #modalTiketResult { position: absolute; left: 0; top: 0; width: 100%; height: 100%; margin: 0; padding: 0; }
        .no-print { display: none !important; }
        .modal-backdrop { display: none !important; }
    }
</style>

{{-- HERO SECTION --}}
<section class="hero pb-5">
    <div class="container-fluid px-lg-5 position-relative">
        <div class="row align-items-center gy-5">
            <div class="col-md-6 px-lg-5 text-center text-md-start">
                <h1 class="fw-bold display-5 text-dark">Selamat Datang</h1>
                <p class="fs-4 text-muted mb-4">
                    di Sistem Antrian Rumah Sakit Umum<br>
                    <strong class="text-success" style="color: #1B9C85 !important;">Anna Medika Madura</strong>
                </p>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-rs btn-lg shadow rounded-pill px-4">Login dulu untuk mengambil antrian</a>
                @else
                    <button type="button" class="btn btn-rs btn-lg shadow rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAntrian">
                        Ambil Antrian Sekarang
                    </button>
                @endguest
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end px-lg-5">
                <img src="{{ asset('images/logors.png') }}" class="img-fluid" style="width: 100%; max-width: 500px; height: auto;">
            </div>
        </div>
    </div>
</section>

{{-- ============================================================== --}}
{{-- 2. PANEL SUKSES & TOMBOL TIKET (MUNCUL JIKA SUCCESS)           --}}
{{-- ============================================================== --}}
@if(session('antrian_baru'))
<section class="mb-5 position-relative" style="z-index: 10; margin-top: -30px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                {{-- Card Notifikasi --}}
                <div class="card border-0 shadow-lg rounded-4 p-4 text-center bg-white">
                    <div class="mb-3">
                        <i class="bi bi-check-circle-fill display-4 text-success"></i>
                    </div>
                    <h4 class="fw-bold mb-2">Pendaftaran Berhasil!</h4>
                    <p class="text-muted mb-4">
                        Halo <strong>{{ session('antrian_baru')['nama_pasien'] }}</strong>, antrian Anda untuk 
                        <strong>{{ session('antrian_baru')['poli'] }}</strong> telah terbit.
                    </p>
                    
                    {{-- Tombol Aksi Responsive --}}
                    <div class="d-grid gap-3 d-sm-flex justify-content-center">
                        {{-- Tombol Lihat Tiket --}}
                        <button type="button" class="btn btn-outline-rs rounded-pill px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTiketResult">
                            <i class="bi bi-ticket-perforated me-2"></i>Lihat Tiket
                        </button>

                        {{-- Tombol Cetak Langsung --}}
                        <button type="button" onclick="printTiket()" class="btn btn-rs rounded-pill px-4 py-2 fw-bold shadow-sm">
                            <i class="bi bi-printer-fill me-2"></i>Cetak PDF
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endif

{{-- INFO SECTION --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-4 text-center">
                <h4 class="fw-bold" style="color: #1B9C85;">Sistem Antrian Online</h4>
                <p class="mb-0 text-secondary" style="max-width: 800px; margin: 0 auto;">
                    Sistem antrian online ini dirancang untuk mempermudah pasien mengambil nomor antrian secara mandiri.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h4 class="fw-bold">Layanan Poliklinik</h4>
            <div style="width: 60px; height: 3px; background: #1B9C85; margin: 10px auto;"></div>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @foreach($polis as $poli)
                <div class="col">
                    <div class="card poli-card text-center h-100 shadow-sm border-0">
                        <div class="card-body py-4">
                            <i class="bi {{ $poli['icon'] }} fs-1 mb-3 d-block" style="color: #1B9C85;"></i>
                            <p class="mb-0 fw-semibold small">{{ $poli['nama'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========================================== --}}
{{-- MODAL HASIL TIKET (HIDDEN BY DEFAULT)      --}}
{{-- ========================================== --}}
@if(session('antrian_baru'))
<div class="modal fade" id="modalTiketResult" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" id="modalTiketContent">
            {{-- Hiasan --}}
            <div style="height: 10px; background: linear-gradient(to right, var(--rs-green), #14806c); border-radius: 8px 8px 0 0;"></div>
            
            <div class="modal-body p-4 text-center bg-white">
                <div class="mb-4">
                    <img src="{{ asset('images/logors.png') }}" alt="Logo" width="40" class="mb-2" onerror="this.style.display='none'">
                    <h5 class="fw-bold mb-0 text-dark">RSU ANNA MEDIKA</h5>
                    <small class="text-muted">Bukti Pendaftaran Online</small>
                </div>

                <div class="border-top border-2 border-secondary border-opacity-25 my-3" style="border-style: dashed !important;"></div>

                <p class="text-uppercase fw-bold text-secondary small mb-1">Nomor Antrian Anda</p>
                <h1 class="display-1 fw-bold mb-2" style="color: var(--rs-green);">
                    {{ session('antrian_baru')['no_antrian'] }}
                </h1>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill mb-4">
                    {{ session('antrian_baru')['poli'] }}
                </span>

                <div class="text-start bg-light p-3 rounded-3 small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Nama Pasien:</span>
                        <span class="fw-bold">{{ session('antrian_baru')['nama_pasien'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Dokter:</span>
                        <span class="fw-bold">{{ session('antrian_baru')['dokter'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tgl. Periksa:</span>
                        <span class="fw-bold">{{ session('antrian_baru')['tgl_kontrol'] }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Estimasi:</span>
                        <span class="fw-bold text-success">{{ session('antrian_baru')['estimasi_jam'] }}</span>
                    </div>
                </div>

                <p class="fst-italic text-muted small mt-4 mb-0">"Terima Kasih Atas Kunjungan Anda"</p>
            </div>

            <div class="modal-footer justify-content-center border-top-0 pb-4 no-print">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                <button type="button" onclick="printTiket()" class="btn btn-rs rounded-pill px-4">
                    <i class="bi bi-printer-fill me-2"></i>Cetak
                </button>
            </div>
        </div>
    </div>
</div>
@endif

{{-- MODAL FORM PENDAFTARAN --}}
<div class="modal fade" id="modalAntrian" tabindex="-1" aria-labelledby="modalAntrianLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" id="modalAntrianLabel" style="color: #1B9C85;">
                    <i class="bi bi-pencil-square me-2"></i>Formulir Pendaftaran Antrian
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4">
                <form action="{{ route('antrian.store') }}" method="POST" id="formAntrian">
                    @csrf
                    
                    {{-- ❗❗ PENAMPIL ERROR (Agar user tau kalau salah isi) ❗❗ --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-pengantar mb-4 shadow-sm">
                        <div class="d-flex">
                            <div class="fs-3 me-3 text-success"><i class="bi bi-info-circle-fill"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Selamat Datang di Pendaftaran Online</h6>
                                <p class="mb-0 small text-secondary">Silakan isi formulir dengan data yang valid.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">NIK (Sesuai KTP)</label>
                            <input type="number" name="nik" value="{{ old('nik') }}" class="form-control rounded-3" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">Nama Lengkap Pasien</label>
                            <input type="text" name="nama_pasien" value="{{ old('nama_pasien') }}" class="form-control rounded-3" required>
                        </div>
                        {{-- TANGGAL LAHIR (DATEPICKER 1) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Tanggal Lahir</label>
                            <div class="input-group">
                                <input type="text" id="input-lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control rounded-start-3 bg-white-force" readonly required>
                                <span class="input-group-text rounded-end-3 text-success" id="btn-lahir"><i class="bi bi-calendar-event"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select rounded-3" required>
                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Nomor HP / WhatsApp</label>
                            <input type="number" name="nomor_hp" value="{{ old('nomor_hp') }}" class="form-control rounded-3" required>
                        </div>
                        {{-- POLI --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Pilih Layanan Poliklinik</label>
                            <select name="poli" id="select-poli" class="form-select rounded-3 mb-2" required>
                                <option value="" selected disabled>Pilih Poliklinik Tujuan</option>
                                @foreach($polis as $p)
                                    <option value="{{ $p['nama'] }}">{{ $p['nama'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- DOKTER --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">Pilih Dokter Spesialis</label>
                            <select name="dokter" id="select-dokter" class="form-select rounded-3 mb-2" required disabled>
                                <option value="" selected disabled>-- Pilih Poliklinik Terlebih Dahulu --</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control rounded-3" rows="2" required>{{ old('alamat') }}</textarea>
                        </div>
                        {{-- TANGGAL KONTROL (DATEPICKER 2) --}}
                        <div class="col-md-12 mt-4">
                            <label class="form-label fw-semibold text-secondary">Tanggal Rencana Kontrol</label>
                            <div class="input-group">
                                <input type="text" id="input-kontrol" name="tanggal_kontrol" value="{{ old('tanggal_kontrol') }}" class="form-control rounded-start-3 bg-white-force" readonly required>
                                <span class="input-group-text rounded-end-3 text-success" id="btn-kontrol"><i class="bi bi-calendar-check"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Keluar</button>
                <button type="submit" form="formAntrian" class="btn btn-rs rounded-pill px-4 shadow-sm">Simpan Pendaftaran</button>
            </div>
        </div>
    </div>
</div>

<script>
    // --- FUNGSI CETAK ---
    function printTiket() {
        var myModalEl = document.getElementById('modalTiketResult');
        var modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
        modal.show();
        setTimeout(function() { window.print(); }, 500);
    }

    // --- RE-OPEN MODAL JIKA ADA ERROR ---
    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() {
            var myModal = new bootstrap.Modal(document.getElementById('modalAntrian'));
            myModal.show();
        });
    @endif

    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. AMBIL DATA DOKTER DARI CONTROLLER
        const dataDokter = @json($doctors);

        const selectPoli = document.getElementById('select-poli');
        const selectDokter = document.getElementById('select-dokter');

        selectPoli.addEventListener('change', function() {
            const poliTerpilih = this.value;
            const daftarDokter = dataDokter[poliTerpilih] || dataDokter['Lainnya'] || [];
            
            selectDokter.innerHTML = '<option value="" selected disabled>-- Pilih Dokter --</option>';

            if (daftarDokter.length > 0) {
                selectDokter.disabled = false;
                daftarDokter.forEach(function(namaDokter) {
                    const option = document.createElement('option');
                    option.value = namaDokter;
                    option.textContent = namaDokter;
                    selectDokter.appendChild(option);
                });
            } else {
                selectDokter.disabled = true;
                selectDokter.innerHTML = '<option value="" selected disabled>Dokter tidak tersedia</option>';
            }
        });

        // 2. CONFIG AIR DATEPICKER
        const localeID = {
            days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
            daysShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
            daysMin: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
            months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
            today: 'Hari Ini', clear: 'Hapus', dateFormat: 'dd-MM-yyyy', timeFormat: 'HH:mm', firstDay: 1
        };

        const customTodayButton = {
            content: 'Hari Ini', className: 'air-datepicker-button', 
            onClick: (dp) => { let date = new Date(); dp.selectDate(date); dp.setViewDate(date); dp.hide(); }
        };

        const dpLahir = new AirDatepicker('#input-lahir', {
            locale: localeID, autoClose: true, maxDate: new Date(), dateFormat: 'dd-MM-yyyy',
            buttons: [customTodayButton, 'clear'], isMobile: false, position: 'bottom left' 
        });

        const dpKontrol = new AirDatepicker('#input-kontrol', {
            locale: localeID, autoClose: true, minDate: new Date(), dateFormat: 'EEEE, dd-MM-yyyy',
            buttons: [customTodayButton, 'clear'], isMobile: false, position: 'top left'
        });

        // Trigger & Click Outside Logic (Sama)
        function triggerCalendar(e, dpInstance) {
            e.stopPropagation(); e.preventDefault(); dpInstance.show();
        }
        document.getElementById('input-lahir').addEventListener('mousedown', (e) => triggerCalendar(e, dpLahir));
        document.getElementById('btn-lahir').addEventListener('mousedown', (e) => triggerCalendar(e, dpLahir));
        document.getElementById('input-kontrol').addEventListener('mousedown', (e) => triggerCalendar(e, dpKontrol));
        document.getElementById('btn-kontrol').addEventListener('mousedown', (e) => triggerCalendar(e, dpKontrol));

        document.addEventListener('mousedown', function(e) {
            const target = e.target;
            const isClickInsideCalendar = target.closest('.air-datepicker-global-container');
            const isInputLahir = target.closest('#input-lahir') || target.closest('#btn-lahir');
            const isInputKontrol = target.closest('#input-kontrol') || target.closest('#btn-kontrol');

            if (!isClickInsideCalendar && !isInputLahir) dpLahir.hide();
            if (!isClickInsideCalendar && !isInputKontrol) dpKontrol.hide();
        });
    });
</script>

@endsection