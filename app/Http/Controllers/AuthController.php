<?php
// <!-- App/Http/Controllers/AuthController.php -->
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str; 
use Illuminate\Auth\Events\Registered; 

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            // Laravel's default Auth::attempt uses 'email' and 'password'
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 🌟 START: Custom Login Error Logic
        
        // 1. Check if the user exists by email first
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // User does not exist, redirect back with a specific error
            return back()->withErrors([
                'email' => 'User with this email does not exist.',
            ])->onlyInput('email');
        }

        // 2. Check if the password is correct
        if (!Auth::attempt($credentials)) {
            // User exists, but password doesn't match
            return back()->withErrors([
                'password' => 'Wrong password. Please try again.',
            ])->onlyInput('email'); // Keep the email address filled out
        }
        
        // 3. Successful login
        $request->session()->regenerate();
        return redirect()->intended('/');
        
        // 🌟 END: Custom Login Error Logic
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $username = $this->generateUniqueUsername($request->name);

        $user = User::create([
            'name' => $request->name,
            'username' => $username, 
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Stops the auto-login and redirects to the login page
        return redirect()->route('login')->with([
            'status' => 'Registration successful! Please log in.',
            'username' => $username 
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    
    private function generateUniqueUsername(string $name): string
    {
        $baseUsername = Str::slug($name, ''); 
        $username = $baseUsername;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
