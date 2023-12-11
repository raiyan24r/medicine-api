<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use function App\Http\Helpers\httpResponse;

class AuthController extends Controller
{
    public function __construct(private readonly AuthServiceInterface $authService)
    {

    }
    public function register(RegisterUserRequest $request): JsonResponse
    {
        try {
            $this->authService->registerNewUser($request->input('name'), $request->input('email'), $request->input('password'));
            return httpResponse(200, 'Successfully registered new user');
        } catch (Exception $e) {
            return httpResponse(500, 'Failed to register new user');
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->loginUser($request->input('email'), $request->input('password'));
            return httpResponse(200, "Successfully logged in", $response);
        } catch (InvalidCredentialsException $e) {
            return httpResponse(401, "Invalid credentials");
        } catch (Exception $e) {
            return httpResponse(500, "Failed to login");
        }
    }
}
