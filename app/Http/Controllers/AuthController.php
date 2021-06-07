<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Mail;
// use App\Mail\Mail;

use Illuminate\Support\Facades\Auth;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        return response()->json([
            'user' => $user,
            'token' => $token,
            'status' => 'Success',
            'message' => 'User logged in'
        ]);
    }

    public function logout() {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'Success',
            'message' => 'Logged out'
        ]);
    }

    public function passwordReset(Request $request) {

        $fields = $request->validate([
            'email' => 'required|string',
        ]);

        $user = User::where('email', $fields['email'])->first();

        $status = Password::sendResetLink(
            $request->only('email')
        );

        $email = $request->input('email');
        $user = DB::select('SELECT * FROM users WHERE email=:email', ['email' => $email]);
        if (empty($user)) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'There is no user with such email address'
            ]);
        }

        $mailObj = new \stdClass();
        $token = Str::random(12);
        $mailObj->path = "http://localhost:{$_SERVER['SERVER_PORT']}/api/auth/password-reset/$token"; // link for the email
        $mailObj->receiver = $user[0]->name;
        Mail::to($email)->send(new ($mailObj));

        // Remember the token
        DB::update('UPDATE users SET remember_token=:token WHERE email=:email', ['token' => $token, 'email' => $email]);

        return response()->json([
            'status' => 'OK',
            'message' => 'A password reset link was send to your email address'
        ]);
    }
}
