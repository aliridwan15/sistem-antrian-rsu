@extends('layouts.admin')

@section('title', 'Data Dokter')

@section('content')

<style>
    /* --- CUSTOM STYLES --- */
    :root {
        --rs-green: #1B9C85;
        --rs-green-dark: #14806c;
        --rs-green-light: #e0f2ef;
    }

    .badge-poli {
        background-color: var(--rs-green-light);
        color: var(--rs-green-dark);
        border: 1px solid var(--rs-green);
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-right: 5px;
        margin-bottom: 5px;
        display: inline-block;
    }

    .btn-rs {
        background-color: var(--rs-green);
        border-color: var(--rs-green);
        color: white;
    }
    .btn-rs:hover {
        background-color: var(--rs-green-dark);
        border-color: var(--rs-green-dark);
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fcfb;
    }
</style>

<div class="container-fluid">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Manajemen Data Dokter</h4>
            <p class="text-muted small mb-0">Kelola daftar dokter (Support Multi-Poli).</p>
        </div>
        <button class="btn btn-rs rounded-pill px-4 shadow-sm" onclick="openCreateModal()">
            <i class="bi bi-plus-lg me-2"></i>Tambah Dokter
        </button>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light border-bottom">
                        <tr>
                            <th class="ps-4 py-3 text-secondary small fw-bold" width="5%">No</th>
                            <th class="py-3 text-secondary small fw-bold" width="35%">Nama Dokter</th>
                            <th class="py-3 text-secondary small fw-bold" width="40%">Poliklinik</th>
                            <th class="pe-4 py-3 text-secondary small fw-bold text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $index => $doctor)
                            <tr>
                                <td class="ps-4 text-muted fw-semibold">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-dark fs-6">{{ $doctor->name }}</span>
                                </td>
                                <td>
                                    @if($doctor->polis->isNotEmpty())
                                        @foreach($doctor->polis as $p)
                                            <span class="badge badge-poli rounded-pill px-3 py-2">
                                                <i class="bi {{ $p->icon ?? 'bi-hospital' }} me-1"></i> {{ $p->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-muted small fst-italic">- Tidak ada poli -</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    {{-- Tombol Edit dengan Data JSON untuk JS --}}
                                    <button class="btn btn-sm btn-outline-warning rounded-pill me-1" 
                                            onclick='openEditModal(@json($doctor))'>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteDoctorModal-{{ $doctor->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            {{-- Delete Modal (Tetap Statis) --}}
                            <div class="modal fade" id="deleteDoctorModal-{{ $doctor->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body text-center p-4">
                                            <div class="mb-3 text-danger">
                                                <i class="bi bi-exclamation-circle display-1"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2">Hapus Data?</h5>
                                            <p class="text-muted small mb-4">
                                                Yakin ingin menghapus <strong>{{ $doctor->name }}</strong>? 
                                                <br><span class="text-danger small">Tindakan ini tidak dapat dibatalkan.</span>
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light rounded-pill w-50" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.dokter.destroy', $doctor->id) }}" method="POST" class="w-50">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill w-100">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted opacity-50 mb-2">
                                        <i class="bi bi-folder-x fs-1"></i>
                                    </div>
                                    <p class="text-muted fw-semibold mb-0">Belum ada data dokter</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- GLOBAL MODAL (Satu Modal untuk Create & Edit) --}}
<div class="modal fade" id="doctorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: var(--rs-green);">Tambah Dokter Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="doctorForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="methodField" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small">Nama Lengkap & Gelar</label>
                        <input type="text" name="name" id="doctorName" class="form-control rounded-3" placeholder="Contoh: dr. Budi Santoso, Sp.A" required>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold text-dark small mb-0">Poliklinik</label>
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addPoliRow()">
                                <i class="bi bi-plus-lg me-1"></i> Tambah Poli
                            </button>
                        </div>
                        
                        {{-- Container untuk baris-baris dropdown poli --}}
                        <div id="poliContainer">
                            {{-- Baris Poli Pertama (Default) --}}
                            <div class="input-group mb-2 poli-row">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-hospital text-muted"></i></span>
                                <select name="poli_id[]" class="form-select border-start-0 text-dark fw-semibold" required>
                                    <option value="" selected disabled>-- Pilih Poliklinik --</option>
                                    @foreach($polis as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-text small text-muted fst-italic">
                            * Klik tombol "Tambah Poli" jika dokter menangani lebih dari satu poli.
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-rs rounded-pill px-4" id="submitBtn">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    // Data Master Poli (Dari PHP ke JS)
    const masterPoli = @json($polis);

    // Fungsi Buka Modal CREATE
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Dokter Baru';
        document.getElementById('doctorForm').action = "{{ route('admin.dokter.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('submitBtn').innerText = 'Simpan Data';
        document.getElementById('doctorName').value = '';
        
        // Reset Poli Container ke 1 baris kosong
        resetPoliContainer();
        addPoliRow(); // Tambah 1 baris default

        new bootstrap.Modal(document.getElementById('doctorModal')).show();
    }

    // Fungsi Buka Modal EDIT
    function openEditModal(doctor) {
        document.getElementById('modalTitle').innerText = 'Edit Data Dokter';
        
        // URL Update: /admin/data-dokter/{id}
        let url = "{{ route('admin.dokter.update', ':id') }}";
        url = url.replace(':id', doctor.id);
        
        document.getElementById('doctorForm').action = url;
        document.getElementById('methodField').value = 'PUT'; // Method Spoofing untuk Update
        document.getElementById('submitBtn').innerText = 'Simpan Perubahan';
        document.getElementById('doctorName').value = doctor.name;

        // Reset dan Isi Ulang Poli
        resetPoliContainer();
        
        if (doctor.polis && doctor.polis.length > 0) {
            // Loop poli yang dimiliki dokter
            doctor.polis.forEach(poli => {
                addPoliRow(poli.id);
            });
        } else {
            addPoliRow(); // Default 1 baris kosong jika tidak ada poli
        }

        new bootstrap.Modal(document.getElementById('doctorModal')).show();
    }

    // Fungsi Tambah Baris Dropdown Poli
    function addPoliRow(selectedId = null) {
        const container = document.getElementById('poliContainer');
        const rowCount = container.children.length;

        const div = document.createElement('div');
        div.className = 'input-group mb-2 poli-row';

        // Buat Dropdown HTML
        let optionsHtml = '<option value="" disabled selected>-- Pilih Poliklinik --</option>';
        masterPoli.forEach(p => {
            const isSelected = (selectedId == p.id) ? 'selected' : '';
            optionsHtml += `<option value="${p.id}" ${isSelected}>${p.name}</option>`;
        });

        // Tombol Hapus (Muncul hanya jika bukan baris pertama)
        // ATAU jika baris pertama tapi user mau menghapusnya (opsional, tapi saya buat minimal sisa 1 baris)
        let deleteBtn = '';
        if (rowCount > 0) {
            deleteBtn = `
                <button type="button" class="btn btn-outline-danger" onclick="removeRow(this)">
                    <i class="bi bi-trash"></i>
                </button>
            `;
        }

        div.innerHTML = `
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-hospital text-muted"></i></span>
            <select name="poli_id[]" class="form-select border-start-0 text-dark fw-semibold" required>
                ${optionsHtml}
            </select>
            ${deleteBtn}
        `;

        container.appendChild(div);
    }

    // Fungsi Hapus Baris
    function removeRow(btn) {
        btn.closest('.poli-row').remove();
    }

    // Fungsi Reset Container
    function resetPoliContainer() {
        document.getElementById('poliContainer').innerHTML = '';
    }
</script>

@endsection