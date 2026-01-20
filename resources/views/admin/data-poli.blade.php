@extends('layouts.admin')

@section('title', 'Data Poliklinik')

@section('content')

<style>
    :root { --rs-green: #1B9C85; --rs-green-dark: #14806c; --rs-green-light: #e0f2ef; }
    
    .btn-rs { background-color: var(--rs-green); border-color: var(--rs-green); color: white; }
    .btn-rs:hover { background-color: var(--rs-green-dark); border-color: var(--rs-green-dark); color: white; }
    .table-hover tbody tr:hover { background-color: #f8fcfb; }
    
    /* Icon Box Style */
    .icon-box {
        width: 35px; height: 35px;
        background-color: var(--rs-green-light);
        color: var(--rs-green);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        font-size: 1.1rem;
    }
</style>

<div class="container-fluid">
    {{-- Header Page --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-0 text-dark">Manajemen Poliklinik</h4>
            <p class="text-muted small mb-0">Kelola data poliklinik dan ikon aplikasi.</p>
        </div>
        <button class="btn btn-rs rounded-pill px-4 shadow-sm" onclick="openCreateModal()">
            <i class="bi bi-plus-lg me-2"></i>Tambah Poli
        </button>
    </div>

    {{-- Notifikasi --}}
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
                            <th class="py-3 text-secondary small fw-bold" width="60%">Nama Poliklinik</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="20%">Kode Icon</th>
                            <th class="py-3 text-secondary small fw-bold text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dataPolis as $index => $poli)
                            <tr>
                                <td class="ps-4 text-muted fw-semibold text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="icon-box">
                                            {{-- Menampilkan Icon Bootstrap --}}
                                            <i class="bi {{ $poli->icon }}"></i>
                                        </div>
                                        <span class="fw-bold text-dark fs-6">{{ $poli->name }}</span>
                                    </div>
                                </td>
                                <td class="text-center text-muted small font-monospace">
                                    {{ $poli->icon }}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-outline-warning rounded-pill me-1" onclick='openEditModal(@json($poli))'>
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill" data-bs-toggle="modal" data-bs-target="#deletePoliModal-{{ $poli->id }}">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>

                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deletePoliModal-{{ $poli->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-sm">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-body text-center p-4">
                                            <div class="mb-3 text-danger"><i class="bi bi-exclamation-triangle display-1"></i></div>
                                            <h5 class="fw-bold mb-2">Hapus Poliklinik?</h5>
                                            <p class="text-muted small mb-4">
                                                Hapus <strong>{{ $poli->name }}</strong>?<br>
                                                <span class="text-danger" style="font-size: 0.75rem;">(Pastikan tidak ada dokter di poli ini)</span>
                                            </p>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-light rounded-pill w-50" data-bs-dismiss="modal">Batal</button>
                                                <form action="{{ route('admin.poli.destroy', $poli->id) }}" method="POST" class="w-50">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill w-100">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada data poliklinik</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- GLOBAL MODAL FORM (Create/Edit) --}}
<div class="modal fade" id="poliModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="poliForm" method="POST" class="modal-content border-0 shadow">
            @csrf
            <input type="hidden" name="_method" id="methodField" value="POST">
            
            <div class="modal-header bg-white border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="modalTitle" style="color: var(--rs-green);">Tambah Poliklinik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                {{-- Nama Poli --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small">Nama Poliklinik</label>
                    <input type="text" name="name" id="poliName" class="form-control rounded-3" placeholder="Contoh: Poli Mata" required>
                </div>

                {{-- Icon Poli --}}
                <div class="mb-3">
                    <label class="form-label fw-bold text-dark small">
                        Icon Bootstrap 
                        <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none small fw-normal ms-1 text-primary">(Lihat Referensi <i class="bi bi-box-arrow-up-right"></i>)</a>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            {{-- Preview Icon --}}
                            <i id="iconPreview" class="bi bi-hospital"></i>
                        </span>
                        <input type="text" name="icon" id="poliIcon" class="form-control rounded-3 border-start-0" 
                               placeholder="Contoh: bi-eye" oninput="updateIconPreview(this.value)">
                    </div>
                    <div class="form-text small">Masukkan nama class icon (contoh: <code>bi-heart</code>, <code>bi-eye</code>).</div>
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
    // Fungsi Buka Modal CREATE
    function openCreateModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Poliklinik';
        document.getElementById('poliForm').action = "{{ route('admin.poli.store') }}";
        document.getElementById('methodField').value = 'POST';
        document.getElementById('submitBtn').innerText = 'Simpan Data';
        
        // Reset Form
        document.getElementById('poliName').value = '';
        document.getElementById('poliIcon').value = '';
        updateIconPreview('bi-hospital'); // Reset icon default

        new bootstrap.Modal(document.getElementById('poliModal')).show();
    }

    // Fungsi Buka Modal EDIT
    function openEditModal(poli) {
        document.getElementById('modalTitle').innerText = 'Edit Poliklinik';
        
        let url = "{{ route('admin.poli.update', ':id') }}";
        url = url.replace(':id', poli.id);
        
        document.getElementById('poliForm').action = url;
        document.getElementById('methodField').value = 'PUT';
        document.getElementById('submitBtn').innerText = 'Simpan Perubahan';
        
        // Isi Form
        document.getElementById('poliName').value = poli.name;
        document.getElementById('poliIcon').value = poli.icon;
        updateIconPreview(poli.icon);

        new bootstrap.Modal(document.getElementById('poliModal')).show();
    }

    // Fungsi Preview Icon Realtime
    function updateIconPreview(iconName) {
        const preview = document.getElementById('iconPreview');
        // Reset dan set class baru
        preview.className = 'bi ' + (iconName || 'bi-hospital');
    }
</script>

@endsection