<?php

namespace App\Erp\Controllers;

use App\Erp\Requests\RubroRequest;
use App\Erp\Requests\StoreTipoPagoRequest;
use App\Erp\Requests\StoreTransaccionRequest;
use App\Erp\Services\RubroService;
use App\Erp\Services\TipoPagoService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class TipoPagoController extends Controller
{
    protected $tipoPagoService;
    // Inyectamos el servicio financiero en el constructor
    public function __construct(TipoPagoService $tipoPagoService)
    {
        $this->tipoPagoService = $tipoPagoService;
    }

    // GET: Obtener el árbol del menú
    public function index()
    {

        //  dd("oui");
        try {
            $rubros = $this->tipoPagoService->getAll();
            return $this->respondOk($rubros, "Tipos de Pago obtenidos exitosamente");
            // return $transaccion;
        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

    public function store(StoreTipoPagoRequest $request): JsonResponse
    {
        try {
            // dd('tilina', $request);
            $news = $this->tipoPagoService->store($request->validated());
            return $this->respondOk($news, "Tipo Pago creada exitosamente");
        } catch (\Exception $e) {
            return $this->parseException($e);
        }
    }
}