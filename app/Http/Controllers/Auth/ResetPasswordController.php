<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view, showing the form with the token pre-filled.
     * This corresponds to the GET 'reset-password/{token}' route.
     */
    public function create(string $token, Request $request)
    {
        // This view needs to be created: resources/views/auth/reset-password.blade.php
        // return view('auth.reset-password', ['token' => $token]);
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email') // jika email dikirim lewat query string
        ]);
    }

    /**
     * Handle the password reset request after the user submits the form.
     * This corresponds to the POST 'reset-password' route.
     */
    public function store(Request $request)
    {
        // 1. Validate the input fields
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8', // Ensures password is confirmed and strong
        ]);

        // 2. Call the Password Broker to perform the reset logic
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // This callback function runs if the token and email are valid
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        // 3. Check the status of the reset
        if ($status == Password::PASSWORD_RESET) {
            // Success: Redirect to login with a success message
            return redirect()->route('login')->with('status', __($status));
        }

        // Failure: Throw a validation exception (e.g., token expired, email invalid)
        throw ValidationException::withMessages([
            'email' => [__($status)],
        ]);
    }
}