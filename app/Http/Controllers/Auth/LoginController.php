<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LogSeverity;
use App\Helpers\ActivityLogger;
use App\Helpers\RedisHelper;
use App\Helpers\UrlManager;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Verification\WhatsappVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'identity' => 'required|string',
            'login_type' => 'required|string',
            'password' => 'nullable|string',
            'otp' => 'nullable|string',
        ]);

        $identity = $request->input('identity');
        $type = $request->input('login_type');
        $ip = $request->ip();

        if (RedisHelper::isBanned($ip)) {
            throw ValidationException::withMessages([
                'identity' => 'IP Anda diblokir sementara karena aktivitas mencurigakan.',
            ]);
        }

        // --- BAGIAN PENTING: SANITASI INPUT ---
        if ($type === 'whatsapp') {
            // Hapus +62/08
            $identity = preg_replace('/^(\+62|62|0)/', '', $identity);
            $identity = preg_replace('/[^0-9]/', '', $identity);
        } elseif ($type === 'username') {
            // Hapus karakter @ di awal string
            // Input "@superadmin" menjadi "superadmin"
            $identity = ltrim($identity, '@');
        }

        // Tentukan Kolom Pencarian
        $field = match ($type) {
            'email' => 'email',
            'username' => 'username',
            'member_id' => 'username', // atau 'id_number' jika ada kolom khusus
            'whatsapp' => 'phone',
            default => 'email'
        };

        // Cari User dengan data yang SUDAH DIBERSIHKAN
        $user = User::where($field, $identity)->first();

        if (! $user) {
            $this->handleFailedLogin($request, 'User tidak ditemukan');
        }

        if ($type === 'whatsapp') {
            $this->loginViaOtp($user, $request->input('otp'));
        } else {
            $this->loginViaPassword($request, $field, $identity, $request->boolean('remember'));
        }

        $request->session()->regenerate();

        ActivityLogger::log(
            'User logged in via ' . strtoupper($type),
            'auth',
            $user,
            ['ip' => $ip, 'user_agent' => $request->userAgent()]
        );

        UrlManager::clearMenuCache($user->id);
        UrlManager::getSidebarMenu();

        return $this->redirectUserBasedOnRole($user);
    }

    /**
     * Helper: Redirect User ke Dashboard yang Tepat
     */
    protected function redirectUserBasedOnRole(User $user)
    {
        // Prioritas 1: Super Admin
        // Karena route admin menggunakan prefix dinamis {panel_role},
        // kita wajib menyertakan parameter tersebut.
        if ($user->hasRole('super-admin')) {
            return redirect()->intended(route('super.dashboard', ['panel_role' => 'super-admin']));
        }

        // Jika nanti Anda ingin role 'admin' atau 'manager' juga masuk ke panel yang sama:
        // $role = $user->roles->first()?->name;
        // if ($role && in_array($role, ['admin', 'finance-manager'])) {
        //     return redirect()->intended(route('super.dashboard', ['panel_role' => $role]));
        // }

        // Default: Dashboard Member Biasa (User Area)
        return redirect()->intended(route('dashboard'));
    }

    protected function loginViaPassword(Request $request, string $field, string $value, bool $remember)
    {
        // Auth::attempt akan men-hash password input dan mencocokkan dengan hash di DB
        if (! Auth::attempt([$field => $value, 'password' => $request->password], $remember)) {
            $this->handleFailedLogin($request, 'Password salah');
        }
    }

    protected function loginViaOtp(User $user, ?string $otp)
    {
        if (empty($otp)) {
            throw ValidationException::withMessages(['otp' => 'Kode OTP wajib diisi.']);
        }

        $verifier = new WhatsappVerification();

        if (! $verifier->verify($user, $otp)) {
            $this->handleFailedLogin(request(), 'Kode OTP salah atau kadaluarsa');
        }

        Auth::login($user);
    }

    protected function handleFailedLogin(Request $request, string $reason)
    {
        ActivityLogger::log(
            "Gagal login: {$reason}",
            'auth-failed',
            null,
            ['input' => $request->only('identity', 'login_type'), 'ip' => $request->ip()],
            LogSeverity::WARNING
        );

        throw ValidationException::withMessages([
            'identity' => trans('auth.failed'),
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user) {
            ActivityLogger::log('User logged out', 'auth', $user);
        }

        return redirect('/');
    }
}
