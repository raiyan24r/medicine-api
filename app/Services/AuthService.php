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

    /**
     * Register a new user with the provided name, email, and password.
     *
     * @param string $name The name of the new user.
     * @param string $email The email address of the new user.
     * @param string $password The password of the new user.
     *
     * @return void
     */
    public function registerNewUser(string $name, string $email, string $password): void
    {
        $this->userRepository->addUser($name, $email, Hash::make($password));
    }

    /**
     * Log in a user with the provided email and password.
     *
     * @param string $email The email address of the user.
     * @param string $password The password of the user.
     *
     * @return array An array containing user details and an access token upon successful login.
     *
     * @throws InvalidCredentialsException If login credentials are invalid.
     */
    public function loginUser(string $email, string $password): array
    {
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'bearer_token' => $token,
            ];
        } else {
            throw new InvalidCredentialsException();
        }
    }
}
