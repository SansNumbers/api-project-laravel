<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;


use Illuminate\Support\Facades\Mail;


use App\Traits\UploadTrait;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use UploadTrait;

    //Display all users (admin only)
    public function index()
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        return User::all();
    }

    //Display the specified resource.
    public function show($id)
    {
        return User::find($id);
    }
 
    //Create new user (admin only)
    public function store(Request $request)
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        $credentials = $request->only(['login', 'password', 'email', 'role', 'name']);

        $validator = Validator::make($credentials, [
            'login' => 'required|unique:App\Models\User,login',
            'password' => 'required|min:6|max:14',
            'email' => 'required|unique:App\Models\User,email',
            'role' => 'in:admin,user'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'FAIL',
                'message' => ($validator->errors())->first()
            ]);
        }

        $credentials['password'] = Hash::make($credentials['password']);

        $user = User::create($credentials);

        return response()->json([
            'status' => 'OK',
            'message' => 'User successfully registered',
        ]);

    }

    //Update the specified resource from storage.
    public function update(Request $request, $id)
    {
        $user = $this->checkAuth();

        if(!$user)
        {
            return response()->json([
                'status' => 'Fail',
                'message' => 'Log in firstly',
            ]);
        }

        if ($user->id != $id && $user->role != 'admin') {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You have no access rights'
            ]);
        }

        $user = User::find($id);
        $user->update($request->all());
        return $user;
    }

    //Set avatar
    public function setAvatar(Request $request) {
        $user_id = auth()->user()->id;
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $avatarName = time().'.'.$request->avatar->extension();
        $request->avatar->move(public_path('storage/images'), $avatarName);
        $path = 'storage/images/'.$avatarName;
        User::whereKey($user_id)->update(['avatar' => $path]);
    
        return asset($path);
    }

    //Remove the specified resource from storage.
    public function destroy($id)
    {
        $user = $this->checkAuth();

        if(!$user)
        {
            return response()->json([
                'status' => 'Fail',
                'message' => 'Log in firstly',
            ]);
        }

        if ($user->id != $id && $user->role != 'admin') {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You have no access rights'
            ]);
        }
        
        return User::destroy($id);
    }
}
