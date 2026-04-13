<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    // Step 1 — Show email form
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Step 2 — Send OTP to email
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:clients,email',
        ], [
            'email.exists' => 'No account found with this email address.',
        ]);

        // Check client is active
        $client = Client::where('email', $request->email)->first();

        if ($client->status !== 'active') {
            return back()->withErrors([
                'email' => 'Your account is not active. Please visit the gym.'
            ]);
        }

        // Store email in session
        session(['reset_email' => $request->email]);

        // Send OTP
        $this->otpService->generate($request->email);

        return redirect()->route('password.otp.form');
    }

    // Step 3 — Show OTP form
    public function showOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.forgot');
        }
        return view('auth.forgot-password-otp');
    }

    // Step 4 — Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.forgot');
        }

        $verified = $this->otpService->verify($email, $request->otp);

        if (!$verified) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // Mark OTP as verified
        session(['reset_otp_verified' => true]);

        return redirect()->route('password.reset.form');
    }

    // Step 5 — Show reset password form
    public function showResetForm()
    {
        if (!session('reset_email') || !session('reset_otp_verified')) {
            return redirect()->route('password.forgot');
        }
        return view('auth.reset-password');
    }

    // Step 6 — Reset password
    public function resetPassword(Request $request)
    {
        if (!session('reset_email') || !session('reset_otp_verified')) {
            return redirect()->route('password.forgot');
        }

        $request->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $email = session('reset_email');

        Client::where('email', $email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Clear session
        session()->forget(['reset_email', 'reset_otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully! Please login with your new password.');
    }
}