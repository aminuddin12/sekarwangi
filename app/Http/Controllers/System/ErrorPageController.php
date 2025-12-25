<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ErrorPageController extends Controller
{
    /**
     * Menampilkan halaman error kustom
     */
    public function show(Request $request)
    {
        // Ambil data dari query parameter atau session
        $code = $request->get('code', 404);
        $message = $request->get('message', 'Halaman yang Anda cari tidak ditemukan atau Anda tersesat.');
        $backUrl = $request->get('back_url', route('home'));

        return Inertia::render('system/error', [
            'code' => $code,
            'message' => $message,
            'backUrl' => $backUrl,
        ]);
    }
}
