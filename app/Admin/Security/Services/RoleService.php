<?php

namespace App\Admin\Security\Services;

use App\Admin\Repository\UserRepository;
use App\Admin\Security\Repository\RoleRepository;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
class RoleService
{
    protected $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAll()
    {
        return $this->roleRepository->getAll();

    }

    public function store($data)
    {
        // dd("dada");
        return $this->roleRepository->store($data);
    }

    public function getById($roleId)
    {
        return $this->roleRepository->store($roleId);
    }



}