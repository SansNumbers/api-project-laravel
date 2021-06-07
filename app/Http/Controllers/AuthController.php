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


    // public function reset_password(Request $request, $token) {
    //     $fields = $request->validate([
    //         'password' => 'required|string|confirmed'
    //     ]);
    //     $user = User::where('remember_token', $token)->first();
    //     $user->update(['password' => bcrypt($fields['password'])]);
    //     return [
    //         'message' => 'Password was changed!'
    //     ];
    // }


    public function passwordReset(Request $request){
        $fileds = $request->validate([
            'email' => 'required|string',
        ]);
        $user = User::where('email', $fileds['email'])->first();

        $token = $user->createToken('resetToken')->plainTextToken;

        $user->update(['remember_token' => $token]);
        
        $details = [
            'title' => 'Link for reset password',
            'body' => URL::current().'/'.$token
        ];

        Mail::to($user)->send(new YourMail($details));

        $user->update([
            'remember_token' => $token 
        ]);

        return [
            'message' => 'Link was sent succeessfully!'
        ];
    }

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
    
    public function logout() {

        auth()->user()->update(['remember_token' => NULL]);

        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out'
        ]);
    }
}
