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

    public function edit(Request $request, $username)
    {
        $user = User::where('name', $username)->first();
    
        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
    
        if ($validator->fails()) {
            return redirect()
                ->route('profile.showUser', ['username' => $username])
                ->withErrors($validator)
                ->withInput();
        }
    
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        $user->save();
    
        return redirect()->route('profile.showUser', ['username' => $username])->with('success', 'Profile updated successfully');
    }

    public function delete($username)
    {
        $user = User::where('name', $username)->first();
    
        if ($user && $user->delete()) {
            return redirect()->route('login')->with('message', 'The account has been deleted.');
        } else {
            return redirect()->route('profile.show', ['username' => $username])->with('error', 'There was a problem deleting the account.');
        }
    }

}
