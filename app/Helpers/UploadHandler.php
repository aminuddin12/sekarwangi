<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadHandler
{
    /**
     * Upload File dengan Nama Unik & Aman
     * @return string Path file yang tersimpan
     */
    public static function upload(UploadedFile $file, string $folder = 'uploads', string $disk = 'public'): string
    {
        // Generate nama file: timestamp-random.ext
        $filename = now()->timestamp . '-' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Simpan
        $path = $file->storeAs($folder, $filename, $disk);

        return $path;
    }

    /**
     * Hapus File dari Storage
     */
    public static function delete(?string $path, string $disk = 'public'): bool
    {
        if (!$path) return false;

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
