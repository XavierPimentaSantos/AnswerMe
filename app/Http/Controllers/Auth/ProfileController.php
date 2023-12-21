<?php


namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
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
        $user = User::where('username', $username)->first();
    
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
        $user = User::where('username', $username)->first();
    
        $settings = Setting::find($user->id);

        $settings->update([
            'hide_nation' => $request->input('show_nation'),
            'hide_birth_date' => $request->input('show_birthdate'),
            'hide_email' => $request->input('show_email'),
            'hide_name' => $request->input('show_name'),
        ]);
        $settings->save();

        if((Auth::user()->id!==$user->id) && (Auth::user()->user_type!=4)) {
            abort(403, 'Unauthorized');
        }

        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $validator = Validator::make($request->all(), [
            'new_name' => 'required|string|max:25',
            'new_username' => 'required|string|max:250|unique:users,username,' . $user->id,
            'new_email' => 'required|string|email|max:250|unique:users,email,' . $user->id,
            'profile_picture' => 'image|mimetypes:image/jpeg,image/png,image/jpg,image/gif|max:2048',
            'new_bio' => 'max:300',
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
            'name' => $request->input('new_name'),
            'username' => $request->input('new_username'),
            'email' => $request->input('new_email'),
            'bio' => $request->input('new_bio'),
            'profile_picture' => $newFileName,
        ]);
        $user->save();
    
        // return redirect()->route('profile.showUser', ['username' => $user->username])->with('success', 'Profile updated successfully');
        return $user->username;
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
