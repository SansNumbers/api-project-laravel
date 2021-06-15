<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Mail\YourMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

use DB;

class AuthController extends Controller
{
    //Registration new user (for all)
    public function register(Request $request) {
        $fields = $request->validate([
            'login' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'login' => $fields['login'],
            'email' => $fields['email'],
            'password' => $fields['password']
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $user->update([
            'remember_token' => $token 
        ]);

        return response()->json([
            'user' => $user,
            'token' => $token,
            'status' => 'Success',
            'message' => 'User has been successfully registered'
        ]);
    }

    //Login user (all users)
    public function login(Request $request) {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('login', $credentials['login'])->first();

        if(!$user || !Hash::check($credentials['password'], $user->password)) {
            return response([
                'message' => 'Bad creds'
            ], 401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $user->update([
            'remember_token' => $token 
        ]);
        
        return response()->json([
            'user' => $user,
            'token' => $token,
            'status' => 'Success',
            'message' => 'User logged in'
        ]);
    }

    //Password reset on email (all users)
    public function passwordReset(Request $request){
        $fields = $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        $token = $user->createToken('resetToken')->plainTextToken;

        $user->update(['remember_token' => $token]);
        
        $details = [
            'title' => 'Link for reset password',
            'body' => 'http://127.0.0.1:3000/password-reset/'.$token
            
        ];

        Mail::to($user)->send(new YourMail($details));

        $user->update([
            'remember_token' => $token 
        ]);

        return [
            'message' => 'Link was sent succeessfully!'
        ];
    }

    //New password (all users)
    public function confirmToken(Request $request, $token) {
        $fields = $request->validate([
            'password' => 'required|string|confirmed'
        ]);

        $user = User::where('remember_token', $token)->first();

        $user->update(['password' => $fields['password']]);

        $user->update(['remember_token' => NULL]);

        return [
            'message' => 'Password was changed!'
        ];
    }
    
    //Logout (for all)
    public function logout() {
        $user = User::where('remember_token', explode('.', explode(' ', request()->header('Authorization'))[1]))->first();
        $user->remember_token = null;
        $user->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out'
        ]);
    }
}
