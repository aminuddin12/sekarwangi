<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateRolePrefix
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // PERBAIKAN: Ambil parameter 'panel_role' (sesuai route baru)
        $routeRole = $request->route('panel_role');

        if (!$user) {
            return redirect()->route('login');
        }

        // Cek apakah user memiliki role yang tertulis di URL
        if (!$user->hasRole($routeRole)) {
            return to_route('system.error.show', [
                'code' => 403,
                'message' => "Akses Ditolak. Anda tidak memiliki otoritas untuk mengakses area '{$routeRole}'.",
                'back_url' => url()->previous(),
            ]);
        }

        // Hapus parameter agar tidak mengganggu Controller
        $request->route()->forgetParameter('panel_role');

        return $next($request);
    }
}
