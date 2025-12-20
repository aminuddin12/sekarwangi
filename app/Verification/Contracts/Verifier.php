<?php

namespace App\Verification\Contracts;

use App\Models\User;

interface Verifier
{
    /**
     * Kirim kode/link verifikasi ke user
     */
    public function send(User $user): void;

    /**
     * Validasi kode/token yang dikirim user
     */
    public function verify(User $user, string $token): bool;
}
