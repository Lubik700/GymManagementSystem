<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserPending;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __construct(protected OtpService $otpService) {}

    // Step 1: Show registration form
    public function showForm()
    {
        return view('auth.register');
    }

    // Step 2: Submit form → send OTP
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'email'            => 'required|email|unique:user_pendings,email|unique:clients,email',
            'contact'          => 'required|string|max:20',
            'dob'              => 'required|date|before:today',
            'address'          => 'required|string',
            'gender'           => 'required|in:male,female,other',
            'password'         => 'required|min:8|confirmed',
            'profile_picture'  => 'nullable|image|max:2048',
            'terms'            => 'accepted',
        ]);

        // Store form data in session temporarily
        $sessionData = $validated;
        unset($sessionData['terms'], $sessionData['password_confirmation']);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('temp_profiles', 'public');
            $sessionData['profile_picture'] = $path;
        }

        $sessionData['password'] = Hash::make($validated['password']);
        session(['pending_registration' => $sessionData]);

        // Send OTP
        $this->otpService->generate($validated['email']);

        return redirect()->route('register.otp.form')
            ->with('email', $validated['email']);
    }

    // Step 3: Show OTP form
    public function showOtpForm()
    {
        if (!session('pending_registration')) {
            return redirect()->route('register');
        }
        return view('auth.otp');
    }

    // Step 4: Verify OTP → save to user_pendings
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $pendingData = session('pending_registration');

        if (!$pendingData) {
            return redirect()->route('register')->withErrors(['otp' => 'Session expired. Please register again.']);
        }

        $verified = $this->otpService->verify($pendingData['email'], $request->otp);

        if (!$verified) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
        }

        // Move profile picture from temp to permanent
        if (isset($pendingData['profile_picture'])) {
            $newPath = str_replace('temp_profiles', 'profile_pictures', $pendingData['profile_picture']);
            Storage::disk('public')->move($pendingData['profile_picture'], $newPath);
            $pendingData['profile_picture'] = $newPath;
        }

        // Save to user_pendings
        UserPending::create($pendingData);
        session()->forget('pending_registration');

        return redirect()->route('register.success');
    }

    // Step 5: Success page
    public function success()
    {
        return view('auth.register-success');
    }

    // Resend OTP
    public function resendOtp()
    {
        $pendingData = session('pending_registration');
        if (!$pendingData) {
            return redirect()->route('register');
        }

        $this->otpService->generate($pendingData['email']);
        return back()->with('success', 'A new OTP has been sent to your email.');
    }
}