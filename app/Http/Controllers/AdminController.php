<?php

namespace App\Http\Controllers;
use App\Models\User; 
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $username = $request->input('username');
        $user = User::where('username', $username)->first();

        if ($user) {
            return view('admin.users.edit', compact('user'));
        } else {
            return redirect()->route('admin.users.index')->with('error', 'User not found.');
        }
    }

}
