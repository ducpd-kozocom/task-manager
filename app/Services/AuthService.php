<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Register a new user
     */
    public function registerUser($data)
    {
        $user = User::create([
            'name' => explode('@', $data['email'])[0], // Using email username as name
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return $user;
    }

    /**
     * Attempt to authenticate user
     */
    public function attemptLogin($credentials)
    {
        return Auth::attempt($credentials);
    }

    /**
     * Log out the current user
     */
    public function logout($request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
