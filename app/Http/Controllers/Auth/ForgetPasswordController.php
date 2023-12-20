<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Carbon;
use Illuminate\Support\Str;

use Illuminate\View\View;

use App\Models\User;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    
    function forgetPassword(){
        return view('auth.forget_password');
    }

    function forgetPasswordPost(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64); 

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);

        Mail::send("mail.forget_password", ['token'=>$token], function($message) use ($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return redirect()->to(route('forget.password'))->with('success', 'We have e-mailed your password reset link!');

    }


    function resetPassword($token){
        return view('new_password', ['token'=>$token]);
    }

    function resetPasswordPost(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required',
        ]);
        $updatePassword = DB::table('password_resets')
                            ->where([
                                'email' => $request->email, 
                                'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword)
            return redirect()->to(route('reset.password'))->with('error', 'Invalid token!');

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect()->to(route('login'))->with('success', 'Your password has been changed!');
    }
}
