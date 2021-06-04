<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // protected function checkAuth() {

    //     if (!request()->header('Authorization'))
    //         return false;

    //     $token = explode('.', explode(' ', request()->header('Authorization'))[1])[2];

    //     $user = User::where('remember_token', $token)->first();
    //     if (!$user)
    //         return false;

    //     return $user;
    // }

    // protected function isHeAdmin() {
    //     if (!request()->header('Authorization'))
    //         return false;

    //     $token = explode('.', explode(' ', request()->header('Authorization'))[1])[2];

    //     $user = User::where('remember_token', $token)->first();

    //     if (!$user || $user->role !== 'admin')
    //         return false;

    //     return $user;
    // }


    public function isAdmin()
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == 'admin') {
                return true;
            } else {
                return false;
            }
        }
    }
}
