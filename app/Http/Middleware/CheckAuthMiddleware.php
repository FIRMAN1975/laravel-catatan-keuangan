<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Redirect ke halaman login
            return redirect()->route('auth.login');
        }

        return $next($request);
    }
}