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
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
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
        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $newFileName = 'profile_' . auth()->user()->id . '.' . $profilePicture->getClientOriginalExtension();
            $profilePicture->move(public_path('profile_pictures'), $newFileName);
            $user->update(['profile_picture' => $newFileName]);
        }
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }


}
