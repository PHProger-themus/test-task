<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function initUser()
    {
        return DB::table('users')->where('id', Auth::id())->get()->first();
    }

    public function getUserByFormData($email, $password)
    {
        $userClass = app(User::class);
        $user = $userClass->where('email', $email)->get()->first();
        if (Hash::check($password, $user->password)) {
            return $user;
        } else {
            return 0;
        }
    }
}
