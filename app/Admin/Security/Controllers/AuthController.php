<?php

namespace App\Admin\Security\Controllers;

use App\Admin\Requests\RegisterRequest;
use App\Admin\Security\Services\AuthService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        // dd('tilina');
        $this->authService = $authService;
    }

    // 📝 REGISTER
    // public function register(RegisterRequest $request)
    // {
    //     try {
    //         $result = $this->authService->register($request->validated());
    //         return $this->respondOk($result, "Usuario Creado Correctamente");
    //     } catch (Exception $e) {
    //         return $this->parseException($e);
    //     }
    // }

    // 🔐 LOGIN
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $result = $this->authService->login($credentials);

            return $this->respondOk($result, "Usuario logeado correctamente");


        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

}
