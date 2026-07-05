<?php

namespace App\Erp\Controllers;

use App\Erp\Requests\StoreTransaccionRequest;
use App\Erp\Services\FinancieroService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Exception;
class TransaccionController extends Controller
{
    protected $financieroService;
    // Inyectamos el servicio financiero en el constructor
    public function __construct(FinancieroService $financieroService)
    {
        $this->financieroService = $financieroService;
    }

    public function store(StoreTransaccionRequest $request): JsonResponse
    {

    // dd("oui");
        try {
            // El request->validated() nos devuelve un ARRAY limpio de PHP con los datos seguros
            $datosValidados = $request->validated();
            // Mandamos el array al servicio para hacer los cálculos e impactar el saldo
            $transaccion = $this->financieroService->procesarTransaccion($datosValidados);

            return $transaccion;
        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

    // public function store(StoreTransaccionRequest $request): JsonResponse
    // {
    //     try {
    //          $datosValidados = $request->validated();

    //         // Mandamos el array al servicio para hacer los cálculos e impactar el saldo
    //         $transaccion = $this->financieroService->procesarTransaccion($datosValidados);
    //     } catch (\Exception $e) {
    //         return $this->parseException($e);
    //     }
    // }



    // GET: Obtener el árbol del menú
    public function index()
    {
        return "perfavore";
    }


}