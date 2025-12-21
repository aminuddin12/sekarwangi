<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ActivityLogger;
use App\Helpers\CodeGenerator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Verification\MemberIdVerification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisterController extends Controller
{
    public function create()
    {
        return Inertia::render('auth/register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'phone' => 'nullable|string|max:20|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => 'required|accepted',
        ]);

        // 1. Buat User Core
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => 'pending', // Default pending approval
        ]);

        // 2. Assign Role Default (Member)
        $user->assignRole('member');

        // 3. Buat Detail Profil Kosong
        $detail = UserDetail::create([
            'user_id' => $user->id,
            'country' => 'Indonesia',
            'join_date' => now(),
        ]);

        // 4. Generate Member ID (Otomatis atau Tunggu Approval)
        // Di sini kita set otomatis generate, tapi status user masih pending
        $verifier = new MemberIdVerification();
        $verifier->send($user);

        // 5. Trigger Event Laravel (Kirim Email Verifikasi)
        event(new Registered($user));

        // 6. Log Aktivitas
        ActivityLogger::log('New user registration', 'auth', $user);

        // 7. Auto Login
        Auth::login($user);

        return redirect(route('dashboard'));
    }
}
