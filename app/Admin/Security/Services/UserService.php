<?php

namespace App\Admin\Security\Services;

use App\Admin\Repository\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        // dd('sasqas');
        $this->userRepository = $userRepository;
    }

    // 📝 REGISTER
    public function register(array $data)
    {

        $da

        // dd('sasqas');
        // Hashear password
        // $data['password'] = Hash::make($data['password']);

        // // Crear usuario
        // $user = $this->userRepository->create($data);

        // // Generar token
        // $token = JWTAuth::fromUser($user);

        // return [
        //     'user' => $user,
        //     'token' => $token
        // ];
    }

    // 🔐 LOGIN
    // public function login(array $credentials)
    // {
    //     if (!$token = JWTAuth::attempt($credentials)) {
    //         throw new Exception('Credenciales inválidas');
    //     }

    //     $user = auth()->user();

    //     return [
    //         'user' => $user,
    //         'token' => $token
    //     ];
    // }
}