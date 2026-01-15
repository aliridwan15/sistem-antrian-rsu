<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Cek apakah user sudah login?
            if (Auth::guard($guard)->check()) {
                
                // Ambil data user yang sedang login
                $user = Auth::user();

                // Bersihkan role dari spasi & huruf besar (agar lebih aman)
                $role = strtolower(trim($user->role));

                // 1. Jika user adalah ADMIN -> Lempar ke Dashboard Admin
                if ($role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                // 2. Jika user adalah PASIEN (atau lainnya) -> Lempar ke Home
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}