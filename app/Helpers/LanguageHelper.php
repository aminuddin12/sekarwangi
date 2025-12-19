<?php

namespace App\Helpers;

use App\Models\SupportedLanguage;
use App\Models\Translation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class LanguageHelper
{
    /**
     * Dapatkan terjemahan berdasarkan Key.
     * Jika tidak ditemukan di DB, fallback ke file JSON atau Key itu sendiri.
     */
    public static function get(string $key, array $replace = [], string $locale = null): string
    {
        $locale = $locale ?? App::getLocale();

        // 1. Coba ambil dari Cache/Database dulu (Prioritas Tertinggi - User Edited)
        $translation = Cache::remember("trans_{$locale}_{$key}", 60 * 60 * 24, function () use ($locale, $key) {
            return Translation::where('locale', $locale)
                ->where('key', $key)
                ->value('value');
        });

        if ($translation) {
            return self::makeReplacements($translation, $replace);
        }

        // 2. Jika tidak ada di DB, ambil dari Laravel Lang File (JSON)
        $fileTranslation = __($key, [], $locale);

        // Jika __() mengembalikan key-nya sendiri, berarti tidak ketemu di file
        if ($fileTranslation !== $key) {
            return self::makeReplacements($fileTranslation, $replace);
        }

        return self::makeReplacements($key, $replace);
    }

    /**
     * Sinkronisasi File JSON ke Database.
     * Berguna dijalankan saat deploy atau via tombol "Sync Language" di Admin.
     */
    public static function sync(): array
    {
        $stats = ['added' => 0, 'skipped' => 0];
        $supportedLocales = SupportedLanguage::pluck('code')->toArray(); // ['id', 'en']

        // Jika tabel supported_languages kosong, default ke id & en
        if (empty($supportedLocales)) {
            $supportedLocales = ['id', 'en'];
        }

        foreach ($supportedLocales as $locale) {
            $path = lang_path("$locale.json");

            if (File::exists($path)) {
                $jsonContent = File::get($path);
                $translations = json_decode($jsonContent, true);

                if (is_array($translations)) {
                    foreach ($translations as $key => $value) {
                        // Cek apakah sudah ada di DB
                        $exists = Translation::where('locale', $locale)
                            ->where('key', $key)
                            ->exists();

                        if (!$exists) {
                            Translation::create([
                                'locale' => $locale,
                                'group' => 'global', // Default group untuk JSON root
                                'key' => $key,
                                'value' => $value,
                                'is_json' => false,
                            ]);
                            $stats['added']++;
                        } else {
                            $stats['skipped']++;
                        }
                    }
                }
            }
        }

        // Clear cache agar data baru terbaca
        Cache::flush();

        return $stats;
    }

    /**
     * Ganti placeholder :name dengan value.
     */
    private static function makeReplacements(string $line, array $replace): string
    {
        if (empty($replace)) {
            return $line;
        }

        foreach ($replace as $key => $value) {
            $line = str_replace(
                [':' . $key, ':' . strtoupper($key), ':' . ucfirst($key)],
                [$value, strtoupper($value), ucfirst($value)],
                $line
            );
        }

        return $line;
    }

    /**
     * Dapatkan daftar bahasa aktif dengan flag-nya.
     */
    public static function getActiveLanguages()
    {
        return Cache::remember('active_languages', 60 * 60 * 24, function () {
            return SupportedLanguage::where('is_active', true)->get();
        });
    }
}
