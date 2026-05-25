<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChangeMenuRequest;
use App\Http\Requests\MenuRequest;
use App\Http\Requests\ModuleRequest;
use App\Services\MenuService;
use App\Services\ModuleService;
use Illuminate\Http\JsonResponse;

class ModuleController extends Controller
{
    protected $moduleService;

    public function __construct(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;
    }

    // GET: Obtener el árbol del menú
    public function index(): JsonResponse
    {
        try {
            $menu = $this->moduleService->getFullMenu();
            return response()->json($menu);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    // POST: Crear un nuevo ítem (Padre o Hijo)
    public function store(ModuleRequest $request): JsonResponse
    {
        try {
            $item = $this->moduleService->storeModule($request->validated());
            return $this->respondOk($item);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

}