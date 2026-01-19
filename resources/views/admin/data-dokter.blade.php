@extends('layouts.admin')

@section('title', 'Data Dokter')

@section('content')

<style>
    :root { --rs-green: #1B9C85; --rs-green-dark: #14806c; --rs-green-light: #e0f2ef; }
    
    .status-dot { height: 10px; width: 10px; border-radius: 50%; display: inline-block; margin-right: 5px; }
    .btn-rs { background-color: var(--rs-green); border-color: var(--rs-green); color: white; }
    .btn-rs:hover { background-color: var(--rs-green-dark); border-color: var(--rs-green-dark); color: white; }
    
    /* Styling Row Jadwal di Tabel Utama */
    .table-hover tbody tr:hover { background-color: #f8fcfb; }
    .schedule-row { border-bottom: 1px solid #f0f0f0; padding: 10px 0; }
    .schedule-row:last-child { border-bottom: none; }
    
    /* Styling Form Modal */
    .poli-row { background-color: #f8f9fa; border: 1px solid #e9ecef; padding: 15px; border-radius: 8px; margin-bottom: 15px; position: relative; }
    .poli-row:hover { border-color: var(--rs-green); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
    .btn-remove-row { position: absolute; top: -10px; right: -10px; width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10; background: white; cursor: pointer; }
    .btn-add-sub-poli { font-size: 0.75rem; text-decoration: none; color: var(--rs-green); display: inline-flex; align-items: center; margin-top: 5px; cursor: pointer; }
    .btn-add-sub-poli:hover { color: var(--rs-green-dark); text-decoration: underline; }
    
    /* Animasi */
    @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .fade-in-up { animation: fadeIn 0.3s ease-out; }
</style>

<div class="container-fluid">
    {{-- Header Page --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Manajemen Data Dokter</h4>
            <p class="text-muted small mb-0">Kelola dokter, poli, dan jadwal praktek.</p>
        </div>

        <div class="d-flex gap-2">
            {{-- FORM FILTER POLI --}}
            <form action="{{ route('admin.dokter.index') }}" method="GET" class="d-flex">
                <select name="poli_id" class="form-select rounded-pill px-3 shadow-sm border-0" 
                        onchange="this.form.submit()" style="min-width: 200px; cursor: pointer;">
                    <option value="">Semua Poliklinik</option>
                    @foreach($polis as $p)
                        <option value="{{ $p->id }}" {{ request('poli_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            {{-- TOMBOL TAMBAH --}}
            <button class="btn btn-rs rounded-pill px-4 shadow-sm text-nowrap" onclick="openCreateModal()">
                <i class="bi bi-plus-lg me-2"></i>Tambah Dokter
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- TABEL DATA --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary small fw-bold text-center" width="5%">No</th>
                            <th class="py-3 text-secondary small fw-bold" width="25%">Nama Dokter</th>
                            <th class="py-3 text-secondary small fw-bold" width="25%">Poliklinik</th>
                            <th class="py-3 text-secondary small fw-bold" width="35%">Jadwal Praktek</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $index => $doctor)
                            <tr>
                                {{-- NO URUT: Gunakan $loop->iteration agar urut 1, 2, 3 meski data disortir di controller --}}
                                <td class="ps-4 text-muted fw-semibold text-center">{{ $loop->iteration }}</td>
                                <td><span class="fw-bold text-dark fs-6">{{ $doctor->name }}</span></td>
                                
                                {{-- KOLOM POLI & JADWAL (Menggunakan Grid Row agar Sejajar) --}}
                                <td colspan="2" class="p-0">
                                    @if($doctor->polis->isNotEmpty())
                                        <div class="container-fluid p-0">
                                            @foreach($doctor->polis as $p)
                                                <div class="row m-0 schedule-row {{ $p->pivot->status == 'OFF' ? 'bg-light text-muted' : '' }}">
                                                    
                                                    {{-- KOLOM KIRI: POLIKLINIK --}}
                                                    <div class="col-5 border-end d-flex align-items-center">
                                                        <span class="fw-bold text-success small">
                                                            <i class="bi {{ $p->icon ?? 'bi-hospital' }} me-1"></i> {{ $p->name }}
                                                        </span>
                                                        @if($p->pivot->status == 'OFF')
                                                            <span class="badge bg-danger ms-2" style="font-size: 0.6rem;">OFF</span>
                                                        @endif
                                                    </div>

                                                    {{-- KOLOM KANAN: JADWAL --}}
                                                    <div class="col-7">
                                                        @if($p->pivot->status == 'OFF')
                                                            <small class="text-danger fst-italic">Sedang Tidak Praktek / Cuti</small>
                                                        @else
                                                            <div class="small text-dark">
                                                                <strong>{{ $p->pivot->day }}</strong> : {{ $p->pivot->time }}
                                                            </div>
                                                            @if($p->pivot->note)
                                                                <div class="text-muted fst-italic" style="font-size: 0.75rem;">
                                                                    <i class="bi bi-info-circle me-1"></i> {{ $p->pivot->note }}
                                                                </div>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="p-3 text-muted small fst-italic text-center">- Belum ada data -</div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning rounded-pill me-1" onclick='openEditModal(@json($doctor))'>
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deleteDoctorModal-{{ $doctor->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteDoctorModal-{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body text-center p-4">
                                            <div class="mb-3 text-danger"><i class="bi bi-exclamation-circle display-1"></i></div>
                                            <h5 class="fw-bold mb-2">Hapus Data?</h5>
                                            <p class="text-muted small mb-4">Hapus <strong>{{ $doctor->name }}</strong>?</p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light rounded-pill w-50" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.dokter.destroy', $doctor->id) }}" method="POST" class="w-50">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill w-100">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">
                                @if(request('poli_id'))
                                    Tidak ada dokter di poli ini.
                                @else
                                    Belum ada data dokter.
                                @endif
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL FORM (Create/Edit) --}}
<div class="modal fade" id="doctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form id="doctorForm" method="POST" class="modal-content border-0 shadow">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: var(--rs-green);">Tambah Dokter Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small">Nama Lengkap & Gelar</label>
                    <input type="text" name="name" id="doctorName" class="form-control rounded-3" placeholder="Contoh: dr. Budi Santoso, Sp.A" required>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                         <div>
                            <label class="form-label fw-bold text-dark small mb-0">Jadwal Praktek & Poliklinik</label>
                            <div class="text-muted" style="font-size: 0.75rem;">Atur jadwal dan poli di bawah ini.</div>
                         </div>
                         <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm" onclick="addPoliRow()">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Jadwal Baru
                        </button>
                    </div>
                    <div id="poliContainer" class="pb-2"></div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 bg-white">
                <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-rs rounded-pill px-4" id="submitBtn">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    const masterPoli = @json($polis);
    let rowCounter = 0; 

    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Dokter Baru';
        document.getElementById('doctorForm').action = "{{ route('admin.dokter.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('submitBtn').innerText = 'Simpan Data';
        document.getElementById('doctorName').value = '';
        document.getElementById('poliContainer').innerHTML = '';
        rowCounter = 0;
        addPoliRow(); 
        new bootstrap.Modal(document.getElementById('doctorModal')).show();
    }

    function openEditModal(doctor) {
        document.getElementById('modalTitle').innerText = 'Edit Data Dokter';
        let url = "{{ route('admin.dokter.update', ':id') }}";
        url = url.replace(':id', doctor.id);
        document.getElementById('doctorForm').action = url;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('submitBtn').innerText = 'Simpan Perubahan';
        document.getElementById('doctorName').value = doctor.name;
        document.getElementById('poliContainer').innerHTML = '';
        rowCounter = 0;
        
        const groupedSchedules = {};
        if (doctor.polis && doctor.polis.length > 0) {
            doctor.polis.forEach(p => {
                const key = `${p.pivot.day || ''}-${p.pivot.time || ''}-${p.pivot.status}-${p.pivot.note || ''}`;
                if (!groupedSchedules[key]) {
                    groupedSchedules[key] = {
                        day: p.pivot.day,
                        time: p.pivot.time,
                        status: p.pivot.status,
                        note: p.pivot.note,
                        poliIds: [] 
                    };
                }
                groupedSchedules[key].poliIds.push(p.id);
            });
            Object.values(groupedSchedules).forEach(group => {
                addPoliRow(group.day, group.time, group.note, group.status, group.poliIds);
            });
        } else {
            addPoliRow();
        }
        new bootstrap.Modal(document.getElementById('doctorModal')).show();
    }

    function addPoliRow(day = null, time = null, note = null, status = 'Aktif', existingPolis = []) {
        const container = document.getElementById('poliContainer');
        const index = rowCounter++; 
        const div = document.createElement('div');
        div.className = 'poli-row shadow-sm fade-in-up';
        
        const isOff = (status === 'OFF');
        const statusActive = !isOff ? 'selected' : '';
        const statusOff = isOff ? 'selected' : '';
        const styleBg = isOff ? '#f8d7da' : '#d1e7dd';
        const styleText = isOff ? '#842029' : '#0f5132';
        const disabledAttr = isOff ? 'readonly style="background-color: #e9ecef;"' : '';

        div.innerHTML = `
            <div class="row g-2">
                <div class="col-md-5 border-end pe-3">
                    <label class="small text-muted mb-1 fw-bold">Poliklinik</label>
                    <div class="sub-poli-wrapper" id="subPoliWrapper-${index}"></div>
                    <div class="btn-add-sub-poli" onclick="addSubPoli(${index})">
                        <i class="bi bi-plus-circle-fill me-1"></i> Tambah Poli Lain
                    </div>
                </div>
                <div class="col-md-7 ps-3">
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="small text-muted mb-1 fw-bold">Status Kehadiran</label>
                            <select name="schedule[${index}][status]" class="form-select form-select-sm fw-bold text-center" 
                                    style="background-color: ${styleBg}; color: ${styleText}; border:none;"
                                    onchange="toggleInputs(this)">
                                <option value="Aktif" ${statusActive}>Aktif</option>
                                <option value="OFF" ${statusOff}>OFF</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted mb-1">Hari</label>
                            <input type="text" name="schedule[${index}][day]" class="form-control form-control-sm input-hari" 
                                   placeholder="Senin" value="${isOff ? '' : (day || '')}" ${disabledAttr}>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted mb-1">Jam</label>
                            <input type="text" name="schedule[${index}][time]" class="form-control form-control-sm input-jam" 
                                   placeholder="08.00 - 12.00" value="${isOff ? '' : (time || '')}" ${disabledAttr}>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted mb-1">Catatan</label>
                            <input type="text" name="schedule[${index}][note]" class="form-control form-control-sm" 
                                   placeholder="Opsional" value="${note || ''}">
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-sm btn-light text-danger btn-remove-row rounded-circle shadow-sm border" 
                    onclick="removeRow(this)" title="Hapus Jadwal Ini">
                <i class="bi bi-x-lg"></i>
            </button>
        `;
        container.appendChild(div);

        if (existingPolis.length > 0) {
            existingPolis.forEach(poliId => addSubPoli(index, poliId));
        } else {
            addSubPoli(index); 
        }
    }

    function addSubPoli(parentIndex, selectedId = null) {
        const wrapper = document.getElementById(`subPoliWrapper-${parentIndex}`);
        const div = document.createElement('div');
        div.className = 'd-flex mb-2 gap-1 align-items-center animate__animated animate__fadeIn'; 

        let optionsHtml = '<option value="" disabled selected>-- Pilih Poli --</option>';
        masterPoli.forEach(p => {
            const isSelected = (selectedId == p.id) ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" ${isSelected}>${p.name}</option>`;
        });

        const deleteBtn = wrapper.children.length > 0 
            ? `<button type="button" class="btn btn-link text-danger p-0 ms-1" onclick="this.parentNode.remove()" title="Hapus poli ini"><i class="bi bi-x-circle"></i></button>` 
            : '';

        div.innerHTML = `
            <select name="schedule[${parentIndex}][poli_id][]" class="form-select form-select-sm" required>
                ${optionsHtml}
            </select>
            ${deleteBtn}
        `;
        wrapper.appendChild(div);
    }

    function toggleInputs(select) {
        const row = select.closest('.row'); 
        const inputHari = row.querySelector('.input-hari');
        const inputJam = row.querySelector('.input-jam');

        if(select.value === 'OFF') {
            select.style.backgroundColor = '#f8d7da';
            select.style.color = '#842029';
            inputHari.setAttribute('readonly', true);
            inputHari.style.backgroundColor = '#e9ecef';
            inputHari.value = ''; 
            inputJam.setAttribute('readonly', true);
            inputJam.style.backgroundColor = '#e9ecef';
            inputJam.value = '';
        } else {
            select.style.backgroundColor = '#d1e7dd';
            select.style.color = '#0f5132';
            inputHari.removeAttribute('readonly');
            inputHari.style.backgroundColor = '';
            inputJam.removeAttribute('readonly');
            inputJam.style.backgroundColor = '';
        }
    }

    function removeRow(btn) {
        if(confirm("Hapus seluruh jadwal ini?")) {
            btn.closest('.poli-row').remove();
            if(document.getElementById('poliContainer').children.length === 0) {
                addPoliRow();
            }
        }
    }
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(5px); }
    to { opacity: 1; transform: translateY(0); }
}
.fade-in-up { animation: fadeIn 0.3s ease-out; }
</style>

@endsection