<?php

namespace App\Services;

use App\Models\Otp;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OtpService
{
    public function generate(string $email): string
    {
        // Invalidate any old unused OTPs for this email
        Otp::where('email', $email)->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        Otp::create([
            'email'      => $email,
            'otp'        => Hash::make($otp),   // store hashed
            'expires_at' => Carbon::now()->addMinutes(10),
            'used'       => false,
        ]);

        Mail::to($email)->send(new OtpMail($otp)); // send plain OTP to user

        return $otp;
    }

    public function verify(string $email, string $otp): bool
    {
        $record = Otp::where('email', $email)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        // No record found, or OTP doesn't match the hash
        if (!$record || !Hash::check($otp, $record->otp)) {
            return false;
        }

        $record->update(['used' => true]);
        return true;
    }
}