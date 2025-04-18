<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\EmailVerificationMail;
use App\Mail\PasswordVerificationMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function view_profile()
    {

        $user = Auth::user();

        return Inertia::render('Staff&Cashier-Profile/Profile', [
            'user' => $user,
        ]);
    }

    /**
     * Send verification code to user's current email
     */
    public function sendOldEmailCode()
    {
        $user = Auth::user();
        $verificationCode = rand(100000, 999999); // Generate 6-digit code

        // Store verification code in session or database
        Session::put('old_email_verification_code', $verificationCode);
        Session::put('old_email_verification_expires_at', now()->addMinutes(15));

        // Send email with verification code using your existing template
        Mail::to($user->email)->send(new EmailVerificationMail($verificationCode, 'Verify Your Current Email'));

        return response()->json(['success' => true]);
    }

    /**
     * Verify the code sent to old email
     */
    public function verifyOldEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string'
        ]);

        $storedCode = Session::get('old_email_verification_code');
        $expiresAt = Session::get('old_email_verification_expires_at');

        if (!$storedCode || !$expiresAt || now()->isAfter($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired.'
            ], 400);
        }

        if ($request->verification_code == $storedCode) {
            // Mark old email as verified in session
            Session::put('old_email_verified', true);
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.'
        ], 400);
    }

    /**
     * Send verification code to user's new email
     */
    public function sendNewEmailCode(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id()
        ]);

        // Check if old email was verified
        if (!Session::get('old_email_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your current email first.'
            ], 400);
        }

        $verificationCode = rand(100000, 999999); // Generate 6-digit code

        // Store verification code and new email in session
        Session::put('new_email', $request->new_email);
        Session::put('new_email_verification_code', $verificationCode);
        Session::put('new_email_verification_expires_at', now()->addMinutes(15));

        // Send email with verification code using your existing template
        Mail::to($request->new_email)->send(new EmailVerificationMail($verificationCode, 'Verify Your New Email'));

        return response()->json(['success' => true]);
    }
    /**
     * Complete the email update process
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
            'verification_code' => 'required|string'
        ]);

        // Check if old email was verified
        if (!Session::get('old_email_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your current email first.'
            ], 400);
        }

        $storedCode = Session::get('new_email_verification_code');
        $storedEmail = Session::get('new_email');
        $expiresAt = Session::get('new_email_verification_expires_at');

        if (!$storedCode || !$storedEmail || !$expiresAt || now()->isAfter($expiresAt)) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired.'
            ], 400);
        }

        if ($request->verification_code != $storedCode || $request->new_email != $storedEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code or email.'
            ], 400);
        }

        // Update user's email
        $user = Auth::user();
        $user->email = $request->new_email;
        $user->save();

        // Clear verification sessions
        Session::forget([
            'old_email_verification_code',
            'old_email_verified',
            'old_email_verification_expires_at',
            'new_email',
            'new_email_verification_code',
            'new_email_verification_expires_at'
        ]);

        return response()->json(['success' => true]);
    }

    public function sendPasswordVerificationCode(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
        ]);

        $user = Auth::user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        // Generate a verification code
        $verificationCode = rand(100000, 999999);

        // Store the verification code in the session
        session(['password_verification_code' => $verificationCode]);
        session(['password_verification_expires_at' => now()->addMinutes(15)]);

        // Send the verification code to the user's email
        Mail::to($user->email)->send(new PasswordVerificationMail($verificationCode, 'Verify Your Email First'));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email.'
        ]);
    }

    /**
     * Verify the password verification code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyPasswordCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required',
        ]);

        $storedCode = session('password_verification_code');
        $expiresAt = session('password_verification_expires_at');

        if (!$storedCode || !$expiresAt) {
            return response()->json([
                'success' => false,
                'message' => 'No verification code found. Please request a new code.'
            ], 422);
        }

        if (now()->isAfter($expiresAt)) {
            // Clear expired verification code
            session()->forget(['password_verification_code', 'password_verification_expires_at']);

            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired. Please request a new code.'
            ], 422);
        }

        if ($request->verification_code != $storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 422);
        }

        // Mark the code as verified
        session(['password_verification_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Verification code is valid.'
        ]);
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|min:8',
            'password_confirmation' => 'required|same:new_password',
        ]);

        // Check if the verification was successful
        if (!session('password_verification_verified')) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your identity first.'
            ], 422);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Clear verification session variables
        session()->forget([
            'password_verification_code',
            'password_verification_expires_at',
            'password_verification_verified'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.'
        ]);
    }



    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
