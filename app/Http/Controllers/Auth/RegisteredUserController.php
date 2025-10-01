<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Str; // <-- MUST BE HERE

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        // Validation is handled by RegisterRequest
        $validated = $request->validated();
        
        // 1. Generate a unique username based on the name
        $username = $this->generateUniqueUsername($validated['name']);

        // 2. Create the user, making sure 'username' is included!
        $user = User::create([
            'name' => $validated['name'],
            'username' => $username, // <--- THIS LINE IS THE FIX
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }

    /**
     * Generates a unique username by slugging the name and appending a counter if needed.
     */
    private function generateUniqueUsername(string $name): string
    {
        // Create the base username (e.g., 'Audya AI' -> 'audyaai')
        $baseUsername = Str::slug($name, ''); 
        $username = $baseUsername;
        $counter = 1;

        // Loop until a unique username is found
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
