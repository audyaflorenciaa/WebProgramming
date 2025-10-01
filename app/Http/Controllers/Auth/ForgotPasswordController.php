<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function create()
    {
        // This view needs to be created: resources/views/auth/forgot-password.blade.php
        return view('auth.forgot-password');
    }

    /**
     * Handle the request to send a password reset link.
     */
    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Send the password reset link (email)
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Check the status of the email sending process
        if ($status == Password::RESET_LINK_SENT) {
            // Success: Redirect back with a status message
            return back()->with('status', __($status));
        }

        // Failure: Throw a validation exception with the error message
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}