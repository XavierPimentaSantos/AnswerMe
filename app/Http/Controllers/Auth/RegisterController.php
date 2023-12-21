<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\UserRegister;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use DateTime;

use App\Models\User;
use App\Models\Setting;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'bio' => 'nullable',
            'birthdate' => 'required',
            'nationality' => 'nullable',
            'password' => 'required|min:8|confirmed'
        ]);

        $settings = Setting::create([
            'dark_mode' => 0,
            'hide_nation' => 0,
            'hide_birth_date' => 0,
            'hide_email' => 0,
            'hide_name' => 0,
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => $request->username,
            'bio' => $request->bio,
            'birthdate' => $request->birthdate,
            'nationality' => $request->nationality,
            'user_type' => 1,
            'preferences' => $settings->id,
        ]);

        event(new UserRegister($request->username, 1));

        if ($request->input('admin_create')) {
            return redirect()->route('admin.show')->with('success', 'User created successfully');
        }

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();

        return redirect(url('/'))
            ->withSuccess('You have successfully registered & logged in!');
    }
}
