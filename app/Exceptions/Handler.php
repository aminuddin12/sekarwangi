<?php

namespace App\Exceptions;

use App\Handlers\LogHandler;
use App\Handlers\ResponseHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler
{
    /**
     * Custom Rendering Logic
     * Dipanggil dari bootstrap/app.php
     */
    public static function render(Request $request, Throwable $e)
    {
        // 1. Cek apakah request mengharapkan JSON (API)
        if ($request->wantsJson() || $request->is('api/*')) {
            return self::handleApiException($e);
        }

        // 2. Jika request biasa (Web/Inertia), biarkan Laravel menanganinya secara default
        // atau kita bisa custom redirect error page di sini jika mau.
        return null;
    }

    /**
     * Custom Reporting Logic (Logging)
     * Dipanggil dari bootstrap/app.php
     */
    public static function report(Throwable $e)
    {
        LogHandler::capture($e);
    }

    /**
     * Handle Format API Errors
     */
    protected static function handleApiException(Throwable $e)
    {
        // Error Validasi
        if ($e instanceof ValidationException) {
            return ResponseHandler::validationError($e->errors());
        }

        // Error Tidak Ditemukan (404)
        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return ResponseHandler::notFound('Data atau Halaman tidak ditemukan.');
        }

        // Error Autentikasi (401)
        if ($e instanceof AuthenticationException) {
            return ResponseHandler::unauthorized('Sesi Anda telah berakhir, silakan login kembali.');
        }

        // Error Umum (500)
        // Di production, jangan tampilkan error asli demi keamanan
        $message = app()->isProduction() ? 'Terjadi kesalahan internal pada server.' : $e->getMessage();

        return ResponseHandler::error($message, 500, [
            'exception' => get_class($e),
            'file' => app()->isProduction() ? null : $e->getFile(),
            'line' => app()->isProduction() ? null : $e->getLine(),
        ]);
    }
}
