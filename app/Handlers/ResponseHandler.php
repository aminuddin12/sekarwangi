<?php

namespace App\Handlers;

use Illuminate\Http\JsonResponse;

class ResponseHandler
{
    /**
     * Respon Sukses (Standardized)
     */
    public static function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => 'success',
                'message' => $message,
            ],
            'data' => $data,
        ], $code);
    }

    /**
     * Respon Error (Standardized)
     */
    public static function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'meta' => [
                'code' => $code,
                'status' => 'error',
                'message' => $message,
            ],
            'data' => null,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Respon Validasi Gagal (422)
     */
    public static function validationError($errors, string $message = 'Validation Failed'): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Respon Tidak Ditemukan (404)
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Respon Unauthorized (401)
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }
}
