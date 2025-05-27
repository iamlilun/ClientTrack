<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Repositories\AuthRepository;

class AuthService
{

    public function __construct(protected AuthRepository $authRepo) {}


    /**
     * Register a new user and return the user and token.
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        $user = $this->authRepo->createUser($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Login a user and return the user and token.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function login(array $data): array
    {
        $user = $this->authRepo->findUserByEmail($data['email']);

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['帳號或密碼錯誤'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return compact('user', 'token');
    }

    /**
     * Logout the user by deleting the current access token.
     *
     * @param User $user
     * @return void
     */
    public function logout($user): void
    {
        $user->currentAccessToken()->delete();
    }
}
