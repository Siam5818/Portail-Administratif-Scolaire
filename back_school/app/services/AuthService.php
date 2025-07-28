<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            return $user;
        }
        return null;
    }

    public function changePassword(User $user, string $newPassword): bool
    {
        $user->password = Hash::make($newPassword);
        $user->must_change_password = false;
        return $user->save();
    }
}
