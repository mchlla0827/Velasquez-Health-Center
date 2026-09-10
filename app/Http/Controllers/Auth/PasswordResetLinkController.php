<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Laravel's password broker verifies the email, generates a
        // secure token, stores it, and dispatches the reset email if
        // (and only if) the account actually exists.
        try {
            Password::sendResetLink(
                $request->only('email')
            );
        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('Password reset email failed to send: ' . $exception->getMessage());

            if (config('app.debug')) {
                return back()->withErrors(['email' => 'Mail error: ' . $exception->getMessage()]);
            }
        }

        // Always the same generic message, regardless of whether the
        // email was found - never reveal which emails are registered.
        return back()->with('status', 'If an account exists with this email address, a password reset link has been sent.');
    }
}
