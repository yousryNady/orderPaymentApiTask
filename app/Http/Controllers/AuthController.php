<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Services\AuthService;
use App\Http\Resources\UserResource;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return new AuthResource($result);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        $result = $this->authService->login($credentials);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return new AuthResource($result);
    }

    public function me()
    {
        return response()->json([
            'success' => true,
            'user' => new UserResource($this->authService->me()),
        ]);
    }
}
