<?php

namespace App\Erp\Services;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\Rubro;
use App\Models\TipoPago;
use App\Models\Transaccion;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;
class TransaccionService
{
    // protected $repository;

    // public function __construct(TransaccionRepositoryInterface $repository)
    // {
    //     $this->repository = $repository;
    // }


    public function getTransaccions()
    {
        return Transaccion::get();

    }

    public function getRubroRendimiento()
    {
        // 1. Cargamos el modelo Rubro con su conteo y sus relaciones mapeadas de golpe
        $rubros = Rubro::withCount('transacciones') //withcount crea el campo transacciones_count
            ->with([
                'transacciones' => function ($query) {
                    $query->select('transaccion_id', 'descripcion', 'rubro_id', 'tipo', 'monto_bruto', 'monto_impuesto', 'monto_retencion', 'monto_comision', 'monto_neto');
                    // si no esta en el select abajo aparecera nulo
                }
            ])
            ->get();

        // 4. Usamos 'map' para formatear la respuesta exactamente como la quieres entregar
        $resultado = $rubros->map(function ($rubro) {
            return [
                'rubro_id' => $rubro->rubro_id,
                'nombre' => $rubro->nombre,
                'total_transacciones' => $rubro->transacciones_count, // Generado por withCount

                // Transformamos las transacciones para inyectarles el número de índice correlativo
                'children' => $rubro->transacciones->map(function ($transaccion, $key) {
                    return [
                        'numero_item' => $key + 1, // El índice inicia en 0, sumamos 1 para que empiece en 1
                        'transaccion_id' => $transaccion->transaccion_id,
                        'descripcion' => $transaccion->descripcion,
                        'rubro_id' => $transaccion->rubro_id,
                        'tipo' => $transaccion->tipo,
                        'monto_bruto' => $transaccion->monto_bruto,
                        'monto_retencion' => $transaccion->monto_retencion,
                        'monto_comision' => $transaccion->monto_comision,
                        'monto_neto' => $transaccion->monto_neto,
                    ];
                })
            ];
        });
        return $resultado;
        // // 5. Retornamos la estructura limpia para tu respuesta JSON
        // return response()->json([
        //     'status_code' => 200,
        //     'success' => true,
        //     'message' => 'Rubros obtenidos exitosamente',
        //     'data' => $resultado
        // ]);
    }


}