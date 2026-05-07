<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Cek apakah user punya role yang diizinkan.
     * Contoh pemakaian di route: middleware('role:admin') atau middleware('role:admin,operator')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Akses ditolak. Halaman ini tidak tersedia untuk role Anda.');
        }

        return $next($request);
    }
}
