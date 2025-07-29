<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompleteProfile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek apakah user sudah login dan apakah profilnya lengkap
        if ($user && (empty($user->phone_number) || $user->password === '')) {
            // Jika belum lengkap, redirect ke halaman tambahan informasi
            return redirect()->route('additional-info');
        }

        // Jika sudah lengkap, lanjutkan ke request berikutnya
        return $next($request);
    }
}
