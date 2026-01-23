@extends('layouts.app')

@section('title', 'Beranda - RSU Anna Medika Madura')

@section('content')

{{-- Assets Datepicker --}}
<link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>

<style>
    :root { --rs-green: #1B9C85; --rs-green-light: #e0f2ef; }
    .btn-rs { background-color: var(--rs-green); border-color: var(--rs-green); color: white; }
    .btn-rs:hover { background-color: #14806c; border-color: #14806c; color: white; }
    .btn-outline-rs { color: var(--rs-green); border-color: var(--rs-green); background: transparent; }
    .btn-outline-rs:hover { background-color: var(--rs-green); color: white; }
    
    .air-datepicker-global-container { z-index: 999999 !important; }
    .air-datepicker-button { color: var(--rs-green); font-weight: bold; }
    .input-group-text, .bg-white-force { background-color: white !important; cursor: pointer; }
    .input-group-text { border-left: 0; } .bg-white-force { border-right: 0; }
    .alert-pengantar { background-color: #f8fffe; border: 1px solid #cbf0ea; border-left: 5px solid var(--rs-green); }
</style>

{{-- HERO SECTION --}}
<section class="hero pb-5">
    <div class="container-fluid px-lg-5 position-relative">
        <div class="row align-items-center gy-5">
            <div class="col-md-6 px-lg-5 text-center text-md-start">
                <h1 class="fw-bold display-5 text-dark">Selamat Datang</h1>
                <p class="fs-4 text-muted mb-4">di Sistem Antrian Rumah Sakit Umum<br><strong class="text-success" style="color: #1B9C85 !important;">Anna Medika Madura</strong></p>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-rs btn-lg shadow rounded-pill px-4">Login dulu untuk mengambil antrian</a>
                @else
                    <button type="button" class="btn btn-rs btn-lg shadow rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAntrian">Ambil Antrian Sekarang</button>
                @endguest
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end px-lg-5">
                <img src="{{ asset('images/logors.png') }}" class="img-fluid" style="width: 100%; max-width: 500px; height: auto;">
            </div>
        </div>
    </div>
</section>

{{-- SECTION INFO --}}
<section class="py-5 bg-white">
    <div class="container">
        <div class="card shadow-sm border-0 bg-light">
            <div class="card-body p-4 text-center">
                <h4 class="fw-bold" style="color: #1B9C85;">Sistem Antrian Online</h4>
                <p class="mb-0 text-secondary" style="max-width: 800px; margin: 0 auto;">Sistem antrian online ini dirancang untuk mempermudah pasien mengambil nomor antrian secara mandiri.</p>
            </div>
        </div>
    </div>
</section>

{{-- SECTION POLI --}}
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5"><h4 class="fw-bold">Layanan Poliklinik</h4><div style="width: 60px; height: 3px; background: #1B9C85; margin: 10px auto;"></div></div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4">
            @foreach($polis as $poli)
                <div class="col">
                    <div class="card poli-card text-center h-100 shadow-sm border-0">
                        <div class="card-body py-4"><i class="bi {{ $poli['icon'] }} fs-1 mb-3 d-block" style="color: #1B9C85;"></i><p class="mb-0 fw-semibold small">{{ $poli['nama'] }}</p></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- MODAL FORM PENDAFTARAN --}}
<div class="modal fade" id="modalAntrian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom-0 pt-4 px-4">
                <h5 class="modal-title fw-bold" style="color: #1B9C85;"><i class="bi bi-pencil-square me-2"></i>Formulir Pendaftaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('antrian.store') }}" method="POST" id="formAntrian">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4"><ul class="mb-0 small">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                    @endif
                    <div class="alert alert-pengantar mb-4 shadow-sm">
                        <div class="d-flex">
                            <div class="fs-3 me-3 text-success"><i class="bi bi-info-circle-fill"></i></div>
                            <div><h6 class="fw-bold mb-1 text-dark">Selamat Datang</h6><p class="mb-0 small text-secondary">Isi formulir dengan data valid.</p></div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">NIK</label><input type="number" name="nik" value="{{ old('nik') }}" class="form-control rounded-3" required></div>
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">Nama Lengkap</label><input type="text" name="nama_pasien" value="{{ old('nama_pasien') }}" class="form-control rounded-3" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">Tgl Lahir</label>
                            <div class="input-group"><input type="text" id="input-lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control rounded-start-3 bg-white-force" readonly required><span class="input-group-text rounded-end-3 text-success" id="btn-lahir"><i class="bi bi-calendar-event"></i></span></div>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select rounded-3" required><option value="" selected disabled>Pilih</option><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">No HP / WA</label><input type="number" name="nomor_hp" value="{{ old('nomor_hp') }}" class="form-control rounded-3" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">Poli Tujuan</label>
                            <select name="poli" id="select-poli" class="form-select rounded-3 mb-2" required><option value="" selected disabled>Pilih Poli</option>@foreach($polis as $p)<option value="{{ $p['nama'] }}">{{ $p['nama'] }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">Dokter</label>
                            <select name="dokter" id="select-dokter" class="form-select rounded-3 mb-2" required disabled><option value="" selected disabled>-- Pilih Poli Dulu --</option></select>
                        </div>
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">Alamat</label><textarea name="alamat" class="form-control rounded-3" rows="2" required>{{ old('alamat') }}</textarea></div>
                        <div class="col-md-12 mt-4"><label class="form-label fw-semibold text-secondary">Tgl Rencana Kontrol</label>
                            <div class="input-group"><input type="text" id="input-kontrol" name="tanggal_kontrol" value="{{ old('tanggal_kontrol') }}" class="form-control rounded-start-3 bg-white-force" readonly required><span class="input-group-text rounded-end-3 text-success" id="btn-kontrol"><i class="bi bi-calendar-check"></i></span></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Keluar</button>
                <button type="submit" form="formAntrian" class="btn btn-rs rounded-pill px-4 shadow-sm">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() { new bootstrap.Modal(document.getElementById('modalAntrian')).show(); });
    @endif

    document.addEventListener("DOMContentLoaded", function() {
        const dataDokter = @json($doctors);
        const selectPoli = document.getElementById('select-poli');
        const selectDokter = document.getElementById('select-dokter');

        selectPoli.addEventListener('change', function() {
            const poli = this.value;
            const list = dataDokter[poli] || dataDokter['Lainnya'] || [];
            selectDokter.innerHTML = '<option value="" selected disabled>-- Pilih Dokter --</option>';
            
            if (list.length > 0) {
                selectDokter.disabled = false;
                list.forEach(nm => {
                    const opt = document.createElement('option'); opt.value = nm; opt.textContent = nm;
                    selectDokter.appendChild(opt);
                });
            } else {
                selectDokter.disabled = true;
                selectDokter.innerHTML = '<option value="" selected disabled>Tidak tersedia</option>';
            }
        });

        // Config Datepicker
        const localeID = {
            days: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
            daysShort: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
            daysMin: ['Mg','Sn','Sl','Rb','Km','Jm','Sb'],
            months: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
            monthsShort: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
            today: 'Hari Ini', clear: 'Hapus', dateFormat: 'dd-MM-yyyy', timeFormat: 'HH:mm', firstDay: 1
        };
        const todayBtn = { content: 'Hari Ini', className: 'air-datepicker-button', onClick: (dp) => { let d = new Date(); dp.selectDate(d); dp.setViewDate(d); dp.hide(); } };
        
        const dpOpt = { locale: localeID, autoClose: true, buttons: [todayBtn, 'clear'], isMobile: false };
        const dpLahir = new AirDatepicker('#input-lahir', { ...dpOpt, maxDate: new Date(), position: 'bottom left' });
        const dpKontrol = new AirDatepicker('#input-kontrol', { ...dpOpt, minDate: new Date(), dateFormat: 'EEEE, dd-MM-yyyy', position: 'top left' });

        const showDp = (e, dp) => { e.stopPropagation(); e.preventDefault(); dp.show(); };
        document.getElementById('input-lahir').onmousedown = (e) => showDp(e, dpLahir);
        document.getElementById('btn-lahir').onmousedown = (e) => showDp(e, dpLahir);
        document.getElementById('input-kontrol').onmousedown = (e) => showDp(e, dpKontrol);
        document.getElementById('btn-kontrol').onmousedown = (e) => showDp(e, dpKontrol);
    });
</script>
@endsection