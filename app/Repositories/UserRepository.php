<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function initUser()
    {
        return User::where('id', Auth::id())->get()->first();
    }

    public function getUserByFormData($email, $password)
    {
        $user = User::where('email', $email)->first();
        if (Hash::check($password, $user->password)) {
            return $user;
        } else {
            return 0;
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    public function getUsers(): \Illuminate\Support\Collection
    {
        return User::select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('id')
            ->get();
    }

    public function storeUser(array $inputs) : void
    {
        $this->updateUser(new User(), $inputs);
    }

    public function updateUser(User $user, array $inputs) : void
    {
        $password = trim($inputs['password']);
        $user->name = $inputs['name'];
        $user->email = $inputs['email'];
        $user->role = $inputs['role'] ?? 0;
        if (!empty($password)) {
            $user->password = Hash::make($inputs['password']);
        }
        $user->save();
    }

    public function destroyUser(User $user) : void
    {
        if ($user->id != Auth::id()) {
            $user->delete();
        }
    }
}
