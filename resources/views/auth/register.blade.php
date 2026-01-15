@extends('layouts.app')

@section('title', 'Daftar Akun - RSU Anna Medika Madura')

@section('content')

{{-- Style Khusus --}}
<style>
    :root {
        --rs-green: #1B9C85;
        --rs-green-dark: #14806c;
    }
    
    .link-masuk {
        color: var(--rs-green) !important;
        text-decoration: none;
        transition: 0.3s;
    }
    .link-masuk:hover {
        color: var(--rs-green-dark) !important;
        text-decoration: underline !important;
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
    
    .form-control:focus {
        border-color: var(--rs-green);
        box-shadow: 0 0 0 0.25rem rgba(27, 156, 133, 0.25);
    }
</style>

<div class="hero min-vh-100 d-flex align-items-center bg-light py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5"> 
                
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        
                        {{-- Header Register --}}
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-person-plus-fill display-1" style="color: #1B9C85;"></i>
                            </div>
                            <h3 class="fw-bold text-dark">Daftar Akun Baru</h3>
                            <p class="text-secondary small">
                                Buat akun untuk mengakses layanan pendaftaran online.<br>
                                <span class="fst-italic">(Satu akun bisa untuk mendaftarkan banyak pasien)</span>
                            </p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            {{-- Input Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold small">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" id="name" 
                                        class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" 
                                        value="{{ old('name') }}" required autofocus placeholder="Contoh: Budi Santoso">
                                </div>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small">Alamat Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" 
                                        class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                        value="{{ old('email') }}" required placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Password --}}
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold small">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" 
                                        class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                        required placeholder="Minimal 8 karakter">
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold small">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                        class="form-control border-start-0 ps-0" 
                                        required placeholder="Ulangi password diatas">
                                </div>
                            </div>

                            {{-- Tombol Daftar --}}
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-rs btn-lg py-2 fw-bold shadow-sm">
                                    Daftar Sekarang
                                </button>
                            </div>

                            {{-- Footer Link --}}
                            <div class="text-center border-top pt-3">
                                <p class="mb-0 small text-secondary">
                                    Sudah punya akun? 
                                    <a href="{{ route('login') }}" class="fw-bold link-masuk">
                                        Silahkan Masuk
                                    </a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection