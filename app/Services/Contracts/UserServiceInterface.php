<?php

namespace App\Services\Contracts;

use App\Models\User;

interface UserServiceInterface
{
    public function createUser(array $data): User;
    public function getUserById(int $id): User;
    public function updateUser(int $id, array $data): User;
    public function deleteUser(int $id): bool;
}
