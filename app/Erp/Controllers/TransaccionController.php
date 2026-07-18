<?php

namespace App\Erp\Controllers;

use App\Erp\Requests\StoreTransaccionRequest;
use App\Erp\Services\FinancieroService;
use App\Erp\Services\TransaccionService;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class TransaccionController extends Controller
{
    protected $financieroService;

    protected $transaccionService;
    // Inyectamos el servicio financiero en el constructor
    public function __construct(FinancieroService $financieroService, TransaccionService $transaccionService)
    {
        $this->financieroService = $financieroService;
        $this->transaccionService = $transaccionService;
    }

    public function store(StoreTransaccionRequest $request)
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


    public function index()
    {

        // dd("oui");
        try {
            $news = $this->transaccionService->getTransaccions();
            return $this->respondOk($news, "Transacciones obtenidas exitosamente");
        } catch (Exception $e) {
            return $this->parseException($e);
        }
    }

    public function getRubroRendimiento()
    {

        try {
            $news = $this->transaccionService->getRubroRendimiento();
            return $this->respondOk($news, "Rubro obtenidas exitosamente");
        } catch (Exception $e) {
            return $this->parseException($e);
        }

    }


    public function getRubroRendimientoSp(Request $request)
    {

        try {

            // Capturamos solo los filtros específicos que nos interesan
            // Si no vienen en la URL, Laravel les asignará null de forma automática
            $filtros = $request->only(['id','nombre', 'tipo']);

            $news = $this->transaccionService->getRubroRendimientoSp($filtros);
            return $this->respondOk($news, "Rubro obtenidas exitosamente");
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
    // public function index()
    // {
    //     return "perfavore";
    // }


}