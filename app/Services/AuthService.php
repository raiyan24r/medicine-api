<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    public function __construct(private readonly UserRepository $userRepository)
    {

    }

    public function registerNewUser(string $name, string $email, string $password): void
    {
        $this->userRepository->addUser($name, $email, Hash::make($password));
    }

    /**
     * @throws InvalidCredentialsException
     */
    public function loginUser(string $email, string $password)
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'access_token' => $token,
            ];
        } else {
            throw new InvalidCredentialsException();
        }
    }
}
