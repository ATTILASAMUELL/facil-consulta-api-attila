<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Collection;

class AuthRepository
{
    public function createUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }

    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    public function updateUser(int $id, array $data): User
    {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser(int $id): bool
    {
        $user = User::find($id);
        return $user ? $user->delete() : false;
    }

    public function findUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function validateCredentials(array $credentials): ?User
    {
        return User::where('email', $credentials['email'])->first();
    }
}
