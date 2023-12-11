<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\AuthService;
use App\Repositories\UserRepository;
use App\Exceptions\InvalidCredentialsException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private UserRepository $userRepository;
    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function testRegisterNewUser()
    {
        $name = 'John Doe';
        $email = 'john@example.com';
        $password = 'secret';

        $this->authService = new AuthService($this->userRepository);

        $this->authService->registerNewUser($name, $email, $password);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function testLoginUserWithValidCredentials()
    {
        $name = 'John Doe';
        $email = 'john@example.com';
        $password = 'secret';

        $this->authService = new AuthService($this->userRepository);
        $this->authService->registerNewUser($name, $email, $password);

        Auth::shouldReceive('attempt')->with(['email' => $email, 'password' => $password])->andReturn(true);

        $user = User::where('email', $email)->first();
        Auth::shouldReceive('user')->andReturn($user);

        $result = $this->authService->loginUser($email, $password);

        $this->assertArrayHasKey('bearer_token', $result);
        $this->assertEquals($email, $result['email']);
    }

    public function testLoginUserWithInvalidCredentials()
    {
        $email = 'john@example.com';
        $password = 'wrong_password';

        Auth::shouldReceive('attempt')->with(['email' => $email, 'password' => $password])->andReturn(false);

        $this->expectException(InvalidCredentialsException::class);
        $this->authService = new AuthService($this->userRepository);
        $this->authService->loginUser($email, $password);
    }
}
