<?php

namespace App\Erp\Controllers;

use App\Erp\Requests\RubroRequest;
use App\Erp\Requests\StoreTransaccionRequest;
use App\Erp\Services\RubroService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class RubroController extends Controller
{
    protected $rubroService;
    // Inyectamos el servicio financiero en el constructor
    public function __construct(RubroService $rubroService)
    {
        $this->rubroService = $rubroService;
    }

    // GET: Obtener el árbol del menú
    public function index()
    {

        // dd("oui");
        try {
            $rubros = $this->rubroService->getAll();
            return $this->respondOk($rubros, "Rubros obtenidos exitosamente");
            // return $transaccion;
        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(RubroRequest $request): JsonResponse
    {
        try {
            // dd('tilina', $request);
            $news = $this->rubroService->store($request->validated());
            return $this->respondOk($news, "Rubro creada exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}