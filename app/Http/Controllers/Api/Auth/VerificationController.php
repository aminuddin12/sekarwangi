<?php

namespace App\Http\Controllers\Api\Auth;

use App\Handlers\ResponseHandler;
use App\Http\Controllers\Controller;
use App\Verification\GoogleEmailVerification;
use App\Verification\WhatsappVerification;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    // --- EMAIL VERIFICATION ---

    /**
     * Kirim ulang link verifikasi email
     */
    public function sendEmailVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return ResponseHandler::error('Email already verified.', 400);
        }

        $verifier = new GoogleEmailVerification();
        $verifier->send($request->user());

        return ResponseHandler::success(null, 'Verification link sent to your email.');
    }

    /**
     * Verifikasi Email (Handler untuk link yang diklik dari email)
     */
    public function verifyEmail(EmailVerificationRequest $request)
    {
        $request->fulfill();

        // Return JSON jika API, atau Redirect jika Web
        if ($request->wantsJson()) {
            return ResponseHandler::success(null, 'Email successfully verified.');
        }

        // Redirect ke dashboard frontend jika diakses via browser
        return redirect(config('app.frontend_url') . '/dashboard?verified=1');
    }

    // --- WHATSAPP OTP ---

    /**
     * Kirim OTP WhatsApp
     */
    public function sendWhatsappOtp(Request $request)
    {
        $user = $request->user();

        if (!$user->phone) {
            return ResponseHandler::error('Phone number not set.', 400);
        }

        $verifier = new WhatsappVerification();
        $verifier->send($user);

        return ResponseHandler::success(null, 'OTP sent to your WhatsApp.');
    }

    /**
     * Verifikasi OTP WhatsApp
     */
    public function verifyWhatsappOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $verifier = new WhatsappVerification();

        if ($verifier->verify($request->user(), $request->otp)) {
            return ResponseHandler::success(null, 'Phone number verified successfully.');
        }

        return ResponseHandler::error('Invalid or expired OTP.', 400);
    }
}
