<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Register a new user and return token + user
     */
    public function register(array $data): array
    {
        $user = $this->authRepository->createUser($data);
        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Attempt login and return token + user
     */
    public function login(array $credentials): ?array
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            return null;
        }

        return [
            'token' => $token,
            'user' => auth()->user(),
        ];
    }

    /**
     * Get authenticated user
     */
    public function me()
    {
        return auth()->user();
    }
}
