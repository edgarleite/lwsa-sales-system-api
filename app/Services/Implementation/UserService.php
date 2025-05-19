<?php

namespace App\Services\Implementation;

use App\Models\User;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;

class UserService implements UserServiceInterface
{
    public function createUser(array $data): User
    {
        // Validar dados
        if (empty($data['email']) || empty($data['password']) || empty($data['name'])) {
            throw new InvalidArgumentException('Dados incompletos para criação de usuário');
        }
        
        // Verificar se o email já está em uso
        if (User::where('email', $data['email'])->exists()) {
            throw new InvalidArgumentException('Este email já está em uso');
        }
        
        // Criar o usuário
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
    
    public function getUserById(int $id): User
    {
        $user = User::find($id);
        
        if (!$user) {
            throw new InvalidArgumentException("Usuário com ID {$id} não encontrado");
        }
        
        return $user;
    }
    
    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);
        
        // Se estiver atualizando o email, verificar se já está em uso
        if (isset($data['email']) && $data['email'] !== $user->email) {
            if (User::where('email', $data['email'])->exists()) {
                throw new InvalidArgumentException('Este email já está em uso');
            }
        }
        
        // Se estiver atualizando a senha, fazer o hash
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        $user->update($data);
        
        return $user;
    }
    
    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        return $user->delete();
    }
}
