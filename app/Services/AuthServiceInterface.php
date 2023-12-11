<?php
namespace App\Services;

use App\Exceptions\InvalidCredentialsException;

interface AuthServiceInterface
{
    public function registerNewUser(string $name, string $email, string $password): void;

    /**
     * @throws InvalidCredentialsException
     */
    public function loginUser(string $email, string $password);
}
