<?php

namespace App\Services\Contracts;

use Illuminate\Http\JsonResponse;

interface AuthServiceInterface
{
    public function login(array $credentials): array;
    public function register(array $data): array;
    public function logout(): bool;
    public function refresh(): array;
    public function getAuthenticatedUser();
    public function respondWithToken(string $token): array;
}