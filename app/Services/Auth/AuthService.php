<?php

namespace App\Services\Auth;

use App\Repositories\Auth\AuthRepository;
use App\DTOs\Auth\CreateUserDTO;
use App\DTOs\Auth\UpdateUserDTO;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Importando a model User
use Illuminate\Database\Eloquent\Collection;

class AuthService
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(CreateUserDTO $dto): User
    {
        $userData = [
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password,
        ];

        return $this->authRepository->createUser($userData);
    }

    public function update(int $id, UpdateUserDTO $dto): User
    {
        $userData = [];
        if ($dto->name) {
            $userData['name'] = $dto->name;
        }
        if ($dto->email) {
            $userData['email'] = $dto->email;
        }
        if ($dto->password) {
            $userData['password'] = Hash::make($dto->password);
        }

        return $this->authRepository->updateUser($id, $userData);
    }

    public function login(array $credentials): array
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new \Exception('Tente novamente mais tarde.');
        }

        $user = $this->authRepository->findUserByEmail($credentials['email']);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        JWTAuth::invalidate(JWTAuth::getToken());
    }

    public function refresh(): string
    {
        return JWTAuth::refresh(JWTAuth::getToken());
    }

    public function profile(): ?User
    {
        return Auth::user();
    }

    public function getAllUsers(): Collection
    {
        return $this->authRepository->getAllUsers();
    }

    public function getUserById(int $id): User
    {
        return $this->authRepository->getUserById($id);
    }

    public function deleteUser(int $id): bool
    {
        return $this->authRepository->deleteUser($id);
    }
}
