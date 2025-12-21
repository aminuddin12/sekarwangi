<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect ke Provider (Google/Github)
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle Callback dari Provider
     */
    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // 1. Cari User berdasarkan Email
            $user = User::where('email', $socialUser->getEmail())->first();

            // 2. Jika User Belum Ada -> Register Otomatis
            if (! $user) {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'google_id' => $provider === 'google' ? $socialUser->getId() : null,
                    'avatar' => $socialUser->getAvatar(), // Simpan URL avatar
                    'password' => Hash::make(Str::random(16)), // Password acak
                    'status' => 'active', // Auto active jika via social? Opsional.
                    'email_verified_at' => now(), // Auto verify email
                ]);

                $user->assignRole('member');

                UserDetail::create(['user_id' => $user->id]);

                ActivityLogger::log("User registered via {$provider}", 'auth', $user);
            } else {
                // Update Google ID jika belum ada
                if ($provider === 'google' && ! $user->google_id) {
                    $user->update(['google_id' => $socialUser->getId()]);
                }
            }

            // 3. Login User
            Auth::login($user);

            ActivityLogger::log("User logged in via {$provider}", 'auth', $user);

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Gagal login dengan sosial media: ' . $e->getMessage());
        }
    }
}
