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
        if (!Auth::user()->isAdmin()) {
            return redirect('/');
        }

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
    
    public function updateProfilePicture(Request $request)  
    {
        $user = Auth::user();
        $profilePicture = $request->file('newProfilePicture');

        if ($profilePicture) {
            $newFileName = 'profile_' . auth()->user()->id . '.' . $profilePicture->getClientOriginalExtension();
            $profilePicture->move(public_path('profile_pictures'), $newFileName);

            $user->update(['profile_picture' => $newFileName]);
            $user->save();

            return response()->json(['profile_picture' => asset('profile_pictures/' . $newFileName)], 200);
        }

        return response()->json(['error' => 'Failed to update profile picture'], 500);
    }

    public function edit(Request $request, $username)
    {
        $user = Auth::user();

        $user = User::where('name', $username)->first();
    
        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'profile_picture' => 'image|mimetypes:image/jpeg,image/png,image/jpg,image/gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return redirect()
                ->route('questions.show')
                ->withErrors($validator)
                ->withInput();
        }

        $profilePicture = $request->file('profile_picture');
        $newFileName = $user->profile_picture;
        if ($profilePicture) {
            $newFileName = 'profile_' . auth()->user()->id . '.' . $profilePicture->getClientOriginalExtension();
            $profilePicture->move(public_path('profile_pictures'), $newFileName);
        }    
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'profile_picture' => $newFileName,
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
