<?php

namespace App\Http\Controllers\Super;

use App\Helpers\ActivityLogger;
use App\Helpers\SystemSetting; // Helper cache kita
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SiteSettingsController extends Controller
{
    public function index()
    {
        // Mengelompokkan setting berdasarkan group (site, finance, system, dll)
        $settings = Setting::orderBy('order')->get()->groupBy('group');

        return Inertia::render('Super/Settings/Index', [
            'groupedSettings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        // Validasi dinamis bisa ditambahkan di sini
        $inputs = $request->all();

        foreach ($inputs as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                // Gunakan Mutator di Model Setting untuk enkripsi otomatis jika perlu
                $setting->value = $value;
                $setting->updated_by = $request->user()->id;
                $setting->save();

                // Hapus cache agar perubahan langsung terasa
                SystemSetting::refresh($key);
            }
        }

        ActivityLogger::log('Updated system settings', 'system', null, [], 'warning');

        return back()->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
