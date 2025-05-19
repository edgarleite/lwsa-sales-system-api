<?php

namespace App\Services\Implementation;

use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class AuthService implements AuthServiceInterface
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function login(array $credentials): array
    {
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            throw new InvalidArgumentException('Credenciais inválidas');
        }

        return $this->respondWithToken($token);
    }

    public function register(array $data): array
    {
        try {
            $user = $this->userService->createUser($data);
            $token = Auth::guard('api')->login($user);
            
            return $this->respondWithToken($token);
        } catch (InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage());
        }
    }

    public function logout(): bool
    {
        Auth::guard('api')->logout();
        return true;
    }

    public function refresh(): array
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }

    public function getAuthenticatedUser()
    {
        return Auth::guard('api')->user();
    }

    public function respondWithToken(string $token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Config::get('jwt.ttl', 60) * 60,
            'user' => Auth::guard('api')->user()
        ];
    }
}
