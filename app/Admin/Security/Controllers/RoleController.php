<?php

namespace App\Admin\Security\Controllers;

use App\Admin\Security\Requests\CreateRoleRequest;
use App\Admin\Security\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSeasonRequest;
use App\Http\Requests\StoreTournamentRequest;
use App\Models\Season;
use App\Models\Tournament;
use App\Services\SeasonService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    protected $RoleService;
    // use ApiResponse;
    public function __construct(
        private RoleService $roleService
    ) {

        // dd("I dont know");
        $this->roleService = $roleService;
    }

    public function index(): JsonResponse
    {
        try {
            $roles = $this->roleService->getAll();
            return $this->respondOk($roles);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function show($roleId)
    {
        try {
            $role = $this->roleService->getById($roleId);
            return $this->respondOk($role, "Rol encontrado");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(CreateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->store($request->validated());
            return $this->respondOk($role, "Rol creado correctamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
    // FALTA ACTUALIZAR Y BORRRADO LOGICO

    // public function update(StoreSeasonRequest $request, $seasonId): JsonResponse
    // {
    //     try {
    //         $updated = $this->roleService->update($seasonId, $request->validated());
    //         return $this->respondOk($updated);
    //     } catch (\Exception $e) {
    //         return $this->parseException($e);
    //     }
    // }

    // public function destroy($seasonId): JsonResponse
    // {
    //     try {
    //         $deleted = $this->roleService->delete($seasonId);
    //         return $this->respondOk($deleted);
    //     } catch (\Exception $e) {
    //         return $this->parseException($e);
    //     }
    // }


}
