<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function addUser(string $name, string $email, string $hashedPassword): void
    {
        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ]);
        $user->save();
    }
}
