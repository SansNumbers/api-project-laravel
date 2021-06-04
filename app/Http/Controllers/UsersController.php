<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;

use App\Traits\UploadTrait;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return User::find($id);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $this->isAdmin();

        if (!$user) {
            return response()->json([
                'status' => 'FAIL',
                'message' => 'You`re not admin'
            ]);
        }

        $credentials = $request->only(['login', 'password', 'email', 'role', 'name', 'avatar']);

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

        if (isset($image_data)) {
            $image = $credentials['avatar'];
            $image_data = 'avatars/' . $user->id . '.png';
            $file = fopen($image_data, "w");
            fwrite($file, base64_decode($image));
            fclose($file);
            $user->avatar = $image_data;
            $user->save();
        }

        return response()->json([
            'status' => 'OK',
            'message' => 'User successfully registered',
        ]);

        // $request->validate([
        //     'name' => 'required',
        //     'login' => 'required',
        //     'password' => 'required|string|confirmed',
        //     'email' => 'required|string|unique:users,email',
        //     'role' => 'required'
        // ]);
        // return User::create($request->all());
    }

    // public function setAvatar(Request $request)
    // {
    //     $request->validate([
    //         'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048'
    //     ]);

    //     $user = optional(Auth::user())->id;

    //     $image_data;



    //     if (isset($image_data)) {
    //         $image = $image_data;
    //         $image_data = 'avatars/' . $user->id . '.png';
    //         $file = fopen($image_data, "w");
    //         fwrite($file, base64_decode($image));
    //         fclose($file);

    //         $user->avatar = $image_data;
    //         $user->save();
    //     }

    //     $user->save();
    //     return [
    //         'message' => 'Profile updated successfully.',
    //     ];
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        // if (!$user) {
        //     return response()->json([
        //         'status' => 'FAIL',
        //         'message' => 'Unauthorized'
        //     ]);
        // }

        $user = User::find($id);
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return User::destroy($id);
    }
}
