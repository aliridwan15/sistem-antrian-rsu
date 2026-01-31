@extends('layouts.app')

@section('title', 'Beranda - RSU Anna Medika Madura')

@section('content')

{{-- Assets Datepicker & SweetAlert --}}
<link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.4.0/air-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    :root { --rs-green: #1B9C85; --rs-green-light: #e0f2ef; }
    .btn-rs { background-color: var(--rs-green); border-color: var(--rs-green); color: white; }
    .btn-rs:hover { background-color: #14806c; border-color: #14806c; color: white; }
    .air-datepicker-global-container { z-index: 999999 !important; }
    .air-datepicker-button { color: var(--rs-green); font-weight: bold; }
    .sunday-red { color: #dc3545 !important; font-weight: bold; }
    .-disabled-.sunday-red { color: #f8d7da !important; }
    .poli-link { text-decoration: none; color: inherit; display: block; }
    .poli-card { transition: all 0.3s ease; background-color: white; border: 1px solid #e0e0e0; }
    .poli-link:hover .poli-card { background-color: var(--rs-green) !important; border-color: var(--rs-green) !important; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(27, 156, 133, 0.4) !important; }
    .poli-link:hover .poli-card i, .poli-link:hover .poli-card p { color: white !important; transition: color 0.3s ease; }
</style>

{{-- HERO SECTION --}}
<section class="hero pb-5">
    <div class="container-fluid px-lg-5 position-relative">
        <div class="row align-items-center gy-5">
            <div class="col-md-6 px-lg-5 text-center text-md-start">
                <h1 class="fw-bold display-5 text-dark">Selamat Datang</h1>
                <p class="fs-4 text-muted mb-4">di Sistem Antrian Rumah Sakit Umum<br><strong class="text-success" style="color: #1B9C85 !important;">Anna Medika Madura</strong></p>
                <button type="button" class="btn btn-rs btn-lg shadow rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalAntrian">Ambil Antrian Sekarang</button>
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end px-lg-5">
                <img src="{{ asset('images/logors.png') }}" class="img-fluid" style="width: 100%; max-width: 500px; height: auto;">
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
                    <a href="{{ route('jadwal.dokter', ['poli' => $poli['nama']]) }}" class="poli-link">
                        <div class="card poli-card text-center h-100 shadow-sm">
                            <div class="card-body py-4">
                                <i class="bi {{ $poli['icon'] }} fs-1 mb-3 d-block" style="color: #1B9C85;"></i>
                                <p class="mb-0 fw-semibold small">{{ $poli['nama'] }}</p>
                            </div>
                        </div>
                    </a>
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
                    @if ($errors->any()) <div class="alert alert-danger mb-4"><ul class="mb-0 small">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div> @endif
                    
                    <div class="alert alert-pengantar mb-4 shadow-sm" style="background-color: #f8fffe; border-left: 5px solid var(--rs-green);">
                        <div class="d-flex align-items-start">
                            <div class="fs-3 me-3 text-success"><i class="bi bi-info-circle-fill"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Informasi Penting</h6>
                                <p class="mb-0 small text-secondary">
                                    Pendaftaran hanya dapat dilakukan maksimal <strong>3 jam</strong> setelah jam praktik dokter dimulai.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">NIK</label><input type="number" name="nik" value="{{ old('nik') }}" class="form-control rounded-3" required></div>
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">Nama Lengkap</label><input type="text" name="nama_pasien" value="{{ old('nama_pasien') }}" class="form-control rounded-3" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">Tgl Lahir</label>
                            <div class="input-group"><input type="text" id="input-lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="form-control rounded-start-3 bg-white-force" readonly required><span class="input-group-text rounded-end-3 text-success" id="btn-lahir"><i class="bi bi-calendar-event"></i></span></div>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select rounded-3" required>
                                <option value="" selected disabled>Pilih Jenis Kelamin</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">No HP / WA</label><input type="number" name="nomor_hp" value="{{ old('nomor_hp') }}" class="form-control rounded-3" required></div>
                        <div class="col-md-6"><label class="form-label fw-semibold text-secondary">Poli Tujuan</label>
                            <select name="poli" id="select-poli" class="form-select rounded-3 mb-2" required><option value="" selected disabled>Pilih Poli</option>@foreach($polis as $p)<option value="{{ $p['nama'] }}">{{ $p['nama'] }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">Dokter</label>
                            <select name="dokter" id="select-dokter" class="form-select rounded-3 mb-2" required disabled><option value="" selected disabled>-- Pilih Poli Dulu --</option></select>
                        </div>
                        <div class="col-md-12"><label class="form-label fw-semibold text-secondary">Alamat</label><textarea name="alamat" class="form-control rounded-3" rows="2" required>{{ old('alamat') }}</textarea></div>
                        
                        <div class="col-md-12 mt-4">
                            <label class="form-label fw-semibold text-secondary">Tgl Rencana Kontrol</label>
                            <div class="input-group">
                                <input type="text" id="input-kontrol" name="tanggal_kontrol" value="{{ old('tanggal_kontrol') }}" 
                                       class="form-control rounded-start-3 bg-white-force" readonly required disabled 
                                       placeholder="Pilih Dokter terlebih dahulu">
                                <span class="input-group-text rounded-end-3 text-success" id="btn-kontrol"><i class="bi bi-calendar-date"></i></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pb-4 px-4">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Keluar</button>
                <button type="submit" form="formAntrian" id="btnSubmitAntrian" class="btn btn-rs rounded-pill px-4 shadow-sm">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    @if ($errors->any())
        document.addEventListener("DOMContentLoaded", function() { new bootstrap.Modal(document.getElementById('modalAntrian')).show(); });
    @endif

    document.addEventListener("DOMContentLoaded", function() {
        // DATA DOKTER SUDAH DI FORMAT H:i OLEH CONTROLLER
        const dataDokter = @json($doctors);
        const selectPoli = document.getElementById('select-poli');
        const selectDokter = document.getElementById('select-dokter');
        const inputKontrol = document.getElementById('input-kontrol');
        const formAntrian = document.getElementById('formAntrian');
        const btnSubmit = document.getElementById('btnSubmitAntrian');
        let availableSchedule = {}; 

        // SUBMIT HANDLER
        formAntrian.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: "Pastikan data sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1B9C85',
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                    formAntrian.submit();
                }
            });
        });

        // KONFIGURASI DATEPICKER
        const localeID = {
            days: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
            daysShort: ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
            daysMin: ['Mg','Sn','Sl','Rb','Km','Jm','Sb'],
            months: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
            monthsShort: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
            today: 'Hari Ini', clear: 'Hapus', dateFormat: 'dd-MM-yyyy', timeFormat: 'HH:mm', firstDay: 1
        };

        const dpLahir = new AirDatepicker('#input-lahir', { locale: localeID, autoClose: true, maxDate: new Date() });
        
        // --- DATEPICKER KONTROL ---
        const dpKontrol = new AirDatepicker('#input-kontrol', { 
            locale: localeID, 
            timepicker: false, 
            dateFormat: 'EEEE, dd-MM-yyyy', 
            minDate: new Date(),
            position: 'top left',
            
            // LOGIKA DISABLE TANGGAL (CLIENT SIDE)
            onRenderCell: function({date, cellType}) {
                if (cellType === 'day') {
                    const dayIndex = date.getDay();
                    const availableDays = Object.keys(availableSchedule).map(Number);
                    
                    // 1. Cek Hari Praktek
                    if (dayIndex === 0) return { disabled: true, classes: 'sunday-red' }; 
                    if (availableDays.length > 0 && !availableDays.includes(dayIndex)) return { disabled: true };

                    // 2. CEK JAM KHUSUS HARI INI
                    const now = new Date(); 
                    const isToday = date.getDate() === now.getDate() && 
                                    date.getMonth() === now.getMonth() && 
                                    date.getFullYear() === now.getFullYear();

                    if (isToday) {
                        const startTimeString = availableSchedule[dayIndex]; // ex: "08:30" (Format H:i dijamin Controller)
                        
                        if (startTimeString) {
                            const [startH, startM] = startTimeString.split(':').map(Number);
                            
                            // Hitung Batas Akhir dalam MENIT (Jam Mulai + 3 Jam = 180 menit)
                            const limitMinutes = ((startH * 60) + startM) + 180; 
                            
                            // Hitung Waktu Sekarang dalam MENIT
                            const currentMinutes = (now.getHours() * 60) + now.getMinutes();

                            // Jika waktu sekarang > batas akhir, disable hari ini
                            if (currentMinutes > limitMinutes) {
                                return { 
                                    disabled: true, 
                                    attrs: { title: 'Pendaftaran ditutup (Lewat 3 jam dari jam praktik)' } 
                                };
                            }
                        }
                    }
                }
            }
        });

        selectPoli.addEventListener('change', function() {
            const poli = this.value;
            const listDokterData = dataDokter[poli] || {};
            selectDokter.innerHTML = '<option value="" selected disabled>-- Pilih Dokter --</option>';
            selectDokter.disabled = true;
            inputKontrol.value = ''; inputKontrol.disabled = true;
            availableSchedule = {}; 

            const namaDokter = Object.keys(listDokterData);
            if (namaDokter.length > 0) {
                selectDokter.disabled = false;
                namaDokter.forEach(nm => {
                    const opt = document.createElement('option'); opt.value = nm; opt.textContent = nm;
                    selectDokter.appendChild(opt);
                });
            } else {
                selectDokter.innerHTML = '<option value="" selected disabled>Tidak tersedia</option>';
            }
        });

        selectDokter.addEventListener('change', function() {
            const poli = selectPoli.value;
            const dokter = this.value;
            if (dataDokter[poli] && dataDokter[poli][dokter]) {
                availableSchedule = dataDokter[poli][dokter]; 
                
                inputKontrol.disabled = false; 
                inputKontrol.placeholder = 'Pilih Tanggal';
                inputKontrol.value = '';
                
                // UPDATE AGAR TANGGAL DI-RENDER ULANG DENGAN JADWAL BARU
                dpKontrol.update(); 
            }
        });

        const showDp = (dp) => { dp.show(); };
        document.getElementById('btn-lahir').onclick = () => showDp(dpLahir);
        document.getElementById('btn-kontrol').onclick = () => { if(!inputKontrol.disabled) showDp(dpKontrol); };
    });
</script>
@endsection