<?php

namespace App\Admin\Security\Services;

// use App\Admin\Repository\UserRepository;

use App\Admin\Security\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        // dd('sasqas');
        $this->userRepository = $userRepository;
    }

    // 🔐 LOGIN
    public function login(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new Exception('Credenciales inválidas');
        }

        $user = auth()->user();

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}