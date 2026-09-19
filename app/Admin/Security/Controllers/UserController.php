<?php

namespace App\Admin\Security\Controllers;
use App\Admin\Requests\RegisterRequest;
use App\Admin\Security\Requests\UserRequest;
use App\Admin\Security\Services\UserService;
use App\Admin\Service\AuthService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        // dd('tilina');
        $this->userService = $userService;
    }

    // 📝 REGISTER
    public function register(UserRequest $request)
    {
        try {
            // dd('pa que la pases bie');
            $result = $this->userService->register($request->validated());
            return $this->respondOk($result, "Usuario regitrado correctamente");
        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

}
