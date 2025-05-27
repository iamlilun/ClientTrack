<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\AuthService;
use App\Repositories\AuthRepository;
use App\Models\User;
use Mockery;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthServiceTest extends TestCase
{

    /** @test */
    public function test_register_creates_user_and_returns_token()
    {
        /** Arrange */
        $data = [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        // Mock user model
        $mockUser = Mockery::mock(User::class)->makePartial();
        $mockUser->shouldReceive('createToken')
            ->once()
            ->with('api-token')
            ->andReturn((object)['plainTextToken' => 'mocked-token']);

        // Mock repository
        $mockRepo = Mockery::mock(AuthRepository::class);
        $mockRepo->shouldReceive('createUser')->with($data)->andReturn($mockUser);


        /** Act */
        $service = new AuthService($mockRepo);
        $result = $service->register($data);

        /** Assert */
        $this->assertEquals('mocked-token', $result['token']);
        $this->assertEquals($mockUser, $result['user']);
    }

    /** @test */
    public function test_login_returns_token_if_credentials_match()
    {
        /** Arrange */
        $data = ['email' => 'user@example.com', 'password' => 'secret'];

        // mock Hash::check()
        Hash::shouldReceive('check')
            ->once()
            ->with('secret', Mockery::type('string'))
            ->andReturn(true);

        // mock user
        $mockUser = Mockery::mock(User::class)->makePartial();
        $mockUser->email = $data['email'];
        $mockUser->password = 'fake-encrypted-password'; // 這不重要了，反正 check 是 mock 的
        $mockUser->shouldReceive('createToken')->once()->andReturn((object)['plainTextToken' => 'token-123']);

        // mock repo
        $mockRepo = Mockery::mock(AuthRepository::class);
        $mockRepo->shouldReceive('findUserByEmail')->with($data['email'])->andReturn($mockUser);

        /** Act */
        $service = new AuthService($mockRepo);
        $result = $service->login($data);

        /** Assert */
        $this->assertEquals('token-123', $result['token']);
    }
}
