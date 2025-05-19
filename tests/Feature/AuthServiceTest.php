<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Implementation\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authService;
    protected $mockUserService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userService = Mockery::mock(UserServiceInterface::class);
        
        // Criar um mock do guard
        $this->guard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        
        // Configurar o Auth facade para retornar o mock do guard quando Auth::guard('api') for chamado
        Auth::shouldReceive('guard')
            ->with('api')
            ->andReturn($this->guard);
        
        $this->authService = new AuthService($this->userService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function test_it_can_login_with_valid_credentials()
    {
        // Configurar
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password'
        ];
        
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        // Configurar o mock do guard para retornar um token quando attempt for chamado
        $this->guard->shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn('valid_token');
        
        // Configurar o mock do guard para retornar o usuário quando user for chamado
        $this->guard->shouldReceive('user')
            ->andReturn($user);
        
        // Executar
        $result = $this->authService->login($credentials);
        
        // Verificar
        $this->assertEquals('valid_token', $result['access_token']);
        $this->assertEquals('bearer', $result['token_type']);
        $this->assertEquals(3600, $result['expires_in']);
        $this->assertEquals($user, $result['user']);
    }

    /** @test */
    public function test_it_throws_exception_for_invalid_credentials()
    {
        // Configurar
        $credentials = [
            'email' => 'invalid@example.com',
            'password' => 'wrong_password'
        ];
        
        // Configurar o mock do guard para retornar false quando attempt for chamado
        $this->guard->shouldReceive('attempt')
            ->with($credentials)
            ->once()
            ->andReturn(false);
        
        // Executar e verificar
        $this->expectException(InvalidArgumentException::class);
        $this->authService->login($credentials);
    }

    /** @test */
    public function test_it_can_register_new_user()
    {
        // Configurar
        $userData = [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password'
        ];
        
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'New User',
            'email' => 'new@example.com'
        ]);
        
        $this->userService->shouldReceive('createUser')
            ->with($userData)
            ->once()
            ->andReturn($user);
        
        // Configurar o mock do guard para retornar um token quando login for chamado
        $this->guard->shouldReceive('login')
            ->with($user)
            ->once()
            ->andReturn('new_token');
        
        // Configurar o mock do guard para retornar o usuário quando user for chamado
        $this->guard->shouldReceive('user')
            ->andReturn($user);
        
        // Executar
        $result = $this->authService->register($userData);
        
        // Verificar
        $this->assertEquals('new_token', $result['access_token']);
        $this->assertEquals('bearer', $result['token_type']);
        $this->assertEquals(3600, $result['expires_in']);
        $this->assertEquals($user, $result['user']);
    }

    /** @test */
    public function test_it_can_logout_user()
    {
        // Configurar o mock do guard para não retornar nada quando logout for chamado
        $this->guard->shouldReceive('logout')
            ->once()
            ->andReturn(null);
        
        // Executar
        $result = $this->authService->logout();
        
        // Verificar
        $this->assertTrue($result);
    }

    /** @test */
    public function test_it_can_refresh_token()
    {
        // Configurar
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        // Configurar o mock do guard para retornar um token quando refresh for chamado
        $this->guard->shouldReceive('refresh')
            ->once()
            ->andReturn('refreshed_token');
        
        // Configurar o mock do guard para retornar o usuário quando user for chamado
        $this->guard->shouldReceive('user')
            ->andReturn($user);
        
        // Executar
        $result = $this->authService->refresh();
        
        // Verificar
        $this->assertEquals('refreshed_token', $result['access_token']);
        $this->assertEquals('bearer', $result['token_type']);
        $this->assertEquals(3600, $result['expires_in']);
        $this->assertEquals($user, $result['user']);
    }

    /** @test */
    public function test_it_can_get_authenticated_user()
    {
        // Configurar
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        // Configurar o mock do guard para retornar o usuário quando user for chamado
        $this->guard->shouldReceive('user')
            ->once()
            ->andReturn($user);
        
        // Executar
        $result = $this->authService->getAuthenticatedUser();
        
        // Verificar
        $this->assertEquals($user, $result);
    }

    /** @test */
    public function test_it_can_respond_with_token()
    {
        // Configurar
        $token = 'test_token';
        $user = User::factory()->make([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com'
        ]);
        
        // Configurar o mock do guard para retornar o usuário quando user for chamado
        $this->guard->shouldReceive('user')
            ->once()
            ->andReturn($user);
        
        // Executar
        $result = $this->authService->respondWithToken($token);
        
        // Verificar
        $this->assertEquals($token, $result['access_token']);
        $this->assertEquals('bearer', $result['token_type']);
        $this->assertEquals(3600, $result['expires_in']);
        $this->assertEquals($user, $result['user']);
    }
}
