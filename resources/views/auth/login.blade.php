@extends('layouts.app')

@section('title', 'Login - RSU Anna Medika Madura')

@section('content')

{{-- Style Khusus --}}
<style>
    :root {
        --rs-green: #1B9C85;
        --rs-green-dark: #14806c;
    }
    
    .link-daftar {
        color: var(--rs-green) !important;
        text-decoration: none;
        transition: 0.3s;
    }
    .link-daftar:hover {
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

<div class="hero min-vh-100 d-flex align-items-center bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5">
                        
                        {{-- Header Login --}}
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="bi bi-person-circle display-1" style="color: #1B9C85;"></i>
                            </div>
                            <h3 class="fw-bold text-dark">Login Akun</h3>
                            <p class="text-secondary small">
                                Masuk untuk mengambil nomor antrian.<br>
                                <span class="fst-italic">(Bisa untuk diri sendiri atau mendaftarkan keluarga)</span>
                            </p>
                        </div>

                        <form method="POST" action="{{ route('login.submit') }}">
    @csrf


                            {{-- Input Email --}}
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold small">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" id="email" 
                                        class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                        value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Input Password --}}
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold small">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-lock"></i></span>
                                    <input type="password" name="password" id="password" 
                                        class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                        required placeholder="******">
                                </div>
                                @error('password')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tombol Login --}}
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-rs btn-lg py-2 fw-bold shadow-sm">
                                    Masuk Sekarang
                                </button>
                            </div>

                            {{-- Footer Link --}}
                            <div class="text-center border-top pt-3">
                                <p class="mb-0 small text-secondary">
                                    Belum punya akun? 
                                    <a href="{{ route('register') }}" class="fw-bold link-daftar">
                                        Daftar Disini
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