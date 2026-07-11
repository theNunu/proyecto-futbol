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
        // return "eso lisin";
        $allRubros = DB::table('rubros')
            ->join('transaccions', 'rubros.rubro_id', '=', 'transaccions.rubro_id')
            ->select('rubros.rubro_id', 'rubros.nombre', DB::raw('COUNT(transaccions.transaccion_id) as total_transacciones'))
            ->groupBy('rubros.rubro_id', 'rubros.nombre')
            ->get();

        $rubrosExistentes = $allRubros->pluck('rubro_id');
        // dd($rubrosExistentes);
        $los = [];
        foreach ($rubrosExistentes as $rubroId) {

            $rubro = Rubro::findOrFail($rubroId);
            Log::info('Usuario procesado con éxito', ['el rubro' => $rubro]);

            // NOTA EL '[]': Esto agrega el nuevo rubro al arreglo sin borrar los anteriores
            $los[] = [
                "children" => $rubro->transacciones->map(function ($transaccion) {
                    return [
                        'transaccion_id' => $transaccion->transaccion_id,
                        'descripcion' => $transaccion->descripcion, // Cambia por tus columnas reales
                        'rubro_id' => $transaccion->rubro_id,
                        'tipo' => $transaccion->tipo,
                        'monto_bruto' => $transaccion->monto_bruto,
                        'monto_retencion' => $transaccion->monto_retencion,
                        // 'created_at' => $transaccion->created_at,
                        // 'updated_at' => $transaccion->updated_at,
                    ];
                })
            ];
        }


        Log::info('todos los rubros', [$los]);

        $newObject = [
            'fecha_conexion' => now()->toDateTimeString(),
            'dispositivo' => 'Móvil',
        ];
        $allRubros[] = $newObject;
        // dd("lass tranacciones", $userRow);
        return [
            $allRubros,
            "rubro existe " => $rubrosExistentes,
            "extraterrestres" => $los
            // "cada transaccion" => $userRow
        ];
        // return $users;

    }
}