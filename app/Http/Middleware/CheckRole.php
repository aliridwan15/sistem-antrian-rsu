<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = strtolower(trim(Auth::user()->role));

        if ($userRole !== strtolower($role)) {
            abort(403, 'ANDA TIDAK MEMILIKI AKSES');
        }

        return $next($request);
    }
}
