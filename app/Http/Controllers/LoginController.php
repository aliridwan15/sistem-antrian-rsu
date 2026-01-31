<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Tampilkan Form Login Admin
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses Login
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3. CEK ROLE (KEAMANAN EKSTRA)
            // Pastikan yang login BENAR-BENAR Admin. 
            // Jika role 'pasien' (sisa data lama) mencoba masuk, tolak & logout.
            if (Auth::user()->role !== 'admin') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akses ditolak. Hanya Admin yang diperbolehkan masuk.',
                ]);
            }

            // 4. Jika Admin, Masuk ke Dashboard
            return redirect()->route('admin.dashboard');
        }

        // 5. Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect kembali ke halaman login admin
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}