<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset code to user's email.
     */
    public function sendResetCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'We could not find an account associated with this email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('error', 'The email address is not registered in our system.');
        }

        $user = User::where('email', $request->email)->first();

        // Rate limiting: prevent sending multiple codes within 1 minute
        if (
            $user->password_reset_last_sent_at &&
            $user->password_reset_last_sent_at->addMinute()->isAfter(now())
        ) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Please wait before requesting another code. Try again in a moment.');
        }

        // Generate a 6-digit verification code
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the code with expiration (10 minutes)
        $user->update([
            'password_reset_code' => $resetCode,
            'password_reset_code_expires_at' => now()->addMinutes(10),
            'password_reset_last_sent_at' => now(),
        ]);

        // Send email with the code
        try {
            Mail::to($user->email)->send(new PasswordResetCodeMail(
                $user->name,
                $resetCode,
                10
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Failed to send verification code. Please try again later.');
        }

        return redirect()->route('password.verify')
            ->with('success', 'Verification code sent to ' . $user->email . '. Please check your email.')
            ->with('email', $user->email);
    }

    /**
     * Show the verification code form.
     */
    public function showVerifyForm(Request $request)
    {
        $email = $request->session()->get('email') ?? $request->query('email');

        if (!$email) {
            return redirect()->route('password.forgot')
                ->with('error', 'Please start the password reset process again.');
        }

        return view('auth.verify-reset-code', ['email' => $email]);
    }

    /**
     * Verify the reset code.
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
            'code' => ['required', 'regex:/^\d{6}$/', 'string'],
        ], [
            'code.regex' => 'The verification code must be 6 digits.',
            'email.exists' => 'User not found.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        Log::info('verifyCode called', ['email' => $request->email, 'code' => $request->code]);

        // Check if code exists and is not expired
        if (!$user->password_reset_code || $user->password_reset_code !== $request->code) {
            Log::info('verifyCode failed: invalid code', ['expected' => $user->password_reset_code ?? null, 'provided' => $request->code]);
            return redirect()->back()
                ->withErrors(['code' => 'The verification code is incorrect. Please try again.'])
                ->withInput($request->only('email'));
        }

        if (now()->isAfter($user->password_reset_code_expires_at)) {
            return redirect()->back()
                ->withErrors(['code' => 'The verification code has expired. Please request a new one.'])
                ->withInput($request->only('email'))
                ->with('show_resend', true);
        }

        // Code is valid, proceed to password reset
        // Store the code in session so it can be used after redirect and page refresh.
        $request->session()->put('password_reset', [
            'email' => $user->email,
            'code' => $user->password_reset_code,
        ]);

        $request->session()->save(); // Ensure session is saved before redirect

        Log::debug('Password reset verified; storing in session and redirecting', [
            'email' => $user->email,
            'code' => $user->password_reset_code,
        ]);

        // Redirect to reset form - use named route without parameters, rely on session
        return redirect()->route('password.reset')
            ->with('success', 'Your identity has been verified. Please create a new password.');
    }

    /**
     * Show the password reset form.
     */
    public function showResetForm(Request $request)
    {
        // Get data from session (set by verifyCode)
        $sessionData = $request->session()->get('password_reset', []);
        $email = $sessionData['email'] ?? null;
        $code = $sessionData['code'] ?? null;

        Log::debug('showResetForm - Session data', ['email' => $email, 'code' => $code]);

        // Validate that we have both email and code in session
        if (!$email || !$code) {
            Log::warning('showResetForm - Missing email or code in session');
            return redirect()->route('password.forgot')
                ->with('error', 'Please start the password reset process again.');
        }

        // Find and validate user
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::warning('showResetForm - User not found', ['email' => $email]);
            return redirect()->route('password.forgot')
                ->with('error', 'User not found. Please try again.');
        }

        // Refresh user to get latest reset code data from database
        $user->refresh();

        Log::debug('showResetForm - User refreshed', [
            'email' => $user->email,
            'has_reset_code' => !empty($user->password_reset_code),
            'code_expires_at' => $user->password_reset_code_expires_at,
        ]);

        // Check if reset code is still present in database
        if (!$user->password_reset_code) {
            Log::warning('showResetForm - Reset code not found in database', ['email' => $email]);
            return redirect()->route('password.forgot')
                ->with('error', 'Password reset code not found. Please request a new one.');
        }

        // Check if code has expired
        if (!$user->password_reset_code_expires_at) {
            Log::warning('showResetForm - Expiration time not set', ['email' => $email]);
            return redirect()->route('password.forgot')
                ->with('error', 'Invalid password reset request. Please try again.');
        }

        if (now()->isAfter($user->password_reset_code_expires_at)) {
            Log::warning('showResetForm - Code expired', [
                'email' => $email,
                'expires_at' => $user->password_reset_code_expires_at,
            ]);
            return redirect()->route('password.forgot')
                ->with('error', 'Your password reset code has expired. Please request a new one.');
        }

        Log::info('showResetForm - Displaying reset password form', ['email' => $email]);
        return view('auth.reset-password', ['email' => $email, 'code' => $code]);
    }

    /**
     * Reset the password.
     */
    public function resetPassword(Request $request)
    {
        $email = $request->input('email');
        $code = $request->input('code');

        if (!$email || !$code) {
            return redirect()->route('password.forgot')
                ->with('error', 'Please start the password reset process again.');
        }

        $user = User::where('email', $email)->first();
        if (!$user || !$user->password_reset_code || $user->password_reset_code !== $code) {
            return redirect()->route('password.forgot')
                ->with('error', 'Invalid or expired password reset request. Please try again.');
        }

        if (now()->isAfter($user->password_reset_code_expires_at)) {
            return redirect()->route('password.forgot')
                ->with('error', 'Your password reset code has expired. Please request a new one.');
        }

        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            ],
            'password_confirmation' => ['required'],
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*?&).',
            'password.confirmed' => 'The passwords do not match.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Verify the email matches (extra safety)
        if ($user->email !== $email) {
            return redirect()->route('password.forgot')
                ->with('error', 'Invalid reset attempt.');
        }

        // Update password and clear reset codes
        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_code' => null,
            'password_reset_code_expires_at' => null,
            'password_reset_last_sent_at' => null,
        ]);

        // Clear stored reset state from session
        $request->session()->forget('password_reset');

        return redirect()->route('login')
            ->with('success', 'Your password has been reset successfully. Please log in with your new password.');
    }

    /**
     * Resend the verification code.
     */
    public function resendCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'We could not find an account associated with this email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();

        // Rate limiting: prevent sending multiple codes within 1 minute
        if (
            $user->password_reset_last_sent_at &&
            $user->password_reset_last_sent_at->addMinute()->isAfter(now())
        ) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Please wait before requesting another code. Try again in a moment.');
        }

        // Generate a new 6-digit verification code
        $resetCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store the new code with expiration (10 minutes)
        $user->update([
            'password_reset_code' => $resetCode,
            'password_reset_code_expires_at' => now()->addMinutes(10),
            'password_reset_last_sent_at' => now(),
        ]);

        // Send email with the new code
        try {
            Mail::to($user->email)->send(new PasswordResetCodeMail(
                $user->name,
                $resetCode,
                10
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email: ' . $e->getMessage());
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Failed to send verification code. Please try again later.');
        }

        return redirect()->back()
            ->withInput($request->only('email'))
            ->with('success', 'A new verification code has been sent to ' . $user->email . '.');
    }
}
