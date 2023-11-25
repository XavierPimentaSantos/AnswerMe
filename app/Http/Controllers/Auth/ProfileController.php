<?php


namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;




class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    public function showUser($username)
    {
        $user = User::where('name', $username)->first();
    
        if ($user) {
            return view('pages.profile', compact('user'));
        } else {
            return redirect('/')->with('error', 'User not found');
        }
    }

    public function index()
    {
        $users = User::all();
        return view('pages.users', compact('users'));
    }

    public function edit(Request $request)
    {

        $user = Auth::user();


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('pages.profile')
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }

}
