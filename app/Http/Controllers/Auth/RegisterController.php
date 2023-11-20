<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use DateTime;

use App\Models\User;

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
        //get date from request
        $date = $request->get('birth_date');

        //update date in request
        $request->offsetSet('birth_date', DateTime::createFromFormat('Y-m-d', $date)->getTimestamp());

        $request->validate([
            'fullname' => 'required|string|max:250',
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'bio' => 'nullable',
            'birth_date' => 'nullable',
            'nationality' => 'nullable',
        ]);

        User::create([
            'fullname' => $request->fullname,
            'username' => $request->username,
            'email' => $request->email,
            'user_password' => Hash::make($request->password),
            'bio' => $request->bio,
            'birth_date' => $request->birth_date,
            'nationality' => $request->nationality,
            'user_type' => 1,
            'user_settings' => Settings::create([
                                    'dark_mode' => 0,
                                    'hide_nation' => 0,
                                    'hide_birth_date' => 0,
                                    'hide_email' => 0,
                                    'hide_name' => 0,
                                    'language' => 0
                                    ])
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('cards')
            ->withSuccess('You have successfully registered & logged in!');
    }
}
