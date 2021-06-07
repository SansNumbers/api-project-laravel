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

    protected function checkAuth() {
        if (!request()->header('Authorization'))
            return false;
        $token = explode('.', explode(' ', request()->header('Authorization'))[1]);
        $user = User::where('remember_token', $token)->first();
        if (!$user)
            return false;
        return $user;
    }

    protected function isAdmin() {
        if (!request()->header('Authorization'))
            return false;
        $token = explode('.', explode(' ', request()->header('Authorization'))[1]);
        $user = User::where('remember_token', $token)->first();
        if (!$user || $user->role !== 'admin')
            return false;
        return $user;
    }
}
