<?php

namespace App\Admin\Controllers;

use App\Admin\Requests\RegisterRequest;
use App\Admin\Service\AuthService;
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
    public function register(RegisterRequest $request)
    {
        try {
            $result = $this->authService->register($request->validated());
            return response()->json($result, 201);
        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

    // 🔐 LOGIN
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            $result = $this->authService->login($credentials);

            return response()->json($result);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }

}
