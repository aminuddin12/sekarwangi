<?php

namespace App\Http\Controllers\Super;

use App\Helpers\ActivityLogger;
use App\Helpers\ApiKeyGenerator;
use App\Helpers\ApiSecretGenerator;
use App\Http\Controllers\Controller;
use App\Models\ApiAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApiManagementController extends Controller
{
    public function index()
    {
        $accounts = ApiAccount::latest()->get();
        return Inertia::render('Super/Api/Index', ['accounts' => $accounts]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'provider' => 'required']);

        $account = ApiAccount::create([
            'name' => $request->name,
            'provider' => $request->provider,
            'api_key' => ApiKeyGenerator::generate(),
            'api_secret' => ApiSecretGenerator::generate(), // Otomatis di-encrypt oleh Model
            'is_active' => true,
            'environment' => 'production'
        ]);

        ActivityLogger::log("Created API Account: {$account->name}", 'system');

        return back()->with('success', 'API Account berhasil dibuat.');
    }

    public function rotateKeys(ApiAccount $account)
    {
        $account->update([
            'api_key' => ApiKeyGenerator::generate(),
            'api_secret' => ApiSecretGenerator::generate(),
        ]);

        ActivityLogger::log("Rotated keys for: {$account->name}", 'system', null, [], 'warning');

        return back()->with('success', 'Kunci API berhasil diperbarui.');
    }

    public function destroy(ApiAccount $account)
    {
        $account->delete();
        return back()->with('success', 'API Account dihapus.');
    }
}
