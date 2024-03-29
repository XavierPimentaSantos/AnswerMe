<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\View\View;

use App\Models\User;

class AdminController extends Controller
{
    public function show()
    {
        if (Auth::user()->isModerator()) {
            return view('pages.admin');
        } else {
            return redirect('/');
        }
    }    

    public function submit(Request $request)
    {
        $name = $request->input('username');
        $user = User::where('username', $name)->first();
    
        if ($user) {
            return redirect()->route('profile.showUser', ['username' => $name]);
        } else {
            return redirect()->route('admin.show')->with('error', 'User not found');
        }
    }

    public function blockUser($username)
    {
        $user = User::where('name', $username)->first();
    
        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $user->user_type = 2;
        $user->save();
    
        return redirect()->route('admin.show')->with('success', 'User blocked');
    }

    public function promoteUser($username)
    {
        $user = User::where('name', $username)->first();
    
        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $user->user_type = 3;
        $user->save();
    
        return redirect()->route('admin.show')->with('success', 'User promoted');
    }

    public function unblockUser($username)
    {
        $user = User::where('name', $username)->first();
    
        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $user->user_type = 1;
        $user->save();
    
        return redirect()->route('admin.show')->with('success', 'User unblocked');
    }

    public function demoteUser($username){
        $user = User::where('name', $username)->first();
    
        if (!$user) {
            return redirect('/')->with('error', 'User not found');
        }
    
        $user->user_type = 1;
        $user->save();
    
        return redirect()->route('admin.show')->with('success', 'User demoted');        
    }
    
    public function addTag(Request $request)
{
    $request->validate([
        'tag' => 'required|unique:tags,name',
    ]);

    $tag = $request->input('tag');
    DB::table('tags')->insert(['name' => $tag]);

    return redirect()->back()->with('success', 'Tag added successfully');
}
    
    public function create()
    {
        return view('pages.create');
    }

}