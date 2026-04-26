<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MenuRequest;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    // GET: Obtener el árbol del menú
    public function index(): JsonResponse
    {
        try {
            $menu = $this->menuService->getFullMenu();
            return response()->json($menu);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }

    // POST: Crear un nuevo ítem (Padre o Hijo)
    public function store(MenuRequest $request): JsonResponse
    {
        try {
            $item = $this->menuService->storeMenu($request->validated());
            return $this->respondOk($item);
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}