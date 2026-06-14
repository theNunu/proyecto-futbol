<?php

namespace App\Erp\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class TransaccionController extends Controller
{
    // protected $moduleService;

    // public function __construct(ModuleService $moduleService)
    // {
    //     $this->moduleService = $moduleService;
    // }

    // GET: Obtener el árbol del menú
    public function index()
    {
        return "perfavore";
        // try {
        //     $menu = $this->moduleService->getFullMenu();
        //     return response()->json($menu);
        // } catch (\Exception $e) {
        //     return $this->parseException($e);
        // }
    }



}