<?php

namespace App\Erp\Services;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\HistorialSaldo;
use App\Models\Rubro;
use App\Models\TipoPago;
use App\Models\Transaccion;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;
class TransaccionService
{
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

            // --- AQUÍ OBTENEMOS EL ÚLTIMO REGISTRO DE TRANSACCIÓN PARA ESTE RUBRO ESPECÍFICO ---
            // Filtramos por el rubro actual y ordenamos descendentemente por la llave primaria de la transacción
            $ultimaTransaccion = \App\Models\Transaccion::where('rubro_id', $rubro->rubro_id)
                ->orderByDesc('transaccion_id')
                ->first();
            $historialSaldo = HistorialSaldo::where('transaccion_id', $ultimaTransaccion->transaccion_id)->first();
            // dd($ultimaTransaccion);
            return [
                'rubro_id' => $rubro->rubro_id,
                'nombre' => $rubro->nombre,
                'totalTransacciones' => $rubro->transacciones_count, // Generado por withCount

                // Si necesitas mostrar datos de ese último registro a nivel de Rubro, puedes mapearlos aquí:
                'ultimoUsoId' => $ultimaTransaccion ? $ultimaTransaccion->transaccion_id : null,
                'ultimoUsoDescripcion' => $ultimaTransaccion ? $ultimaTransaccion->descripcion : 'Sin transacciones',
                'saldoPosterior' => $historialSaldo->saldo_posterior,

                // Transformamos las transacciones para inyectarles el número de índice correlativo
                'children' => $rubro->transacciones->map(function ($transaccion, $key) {
                    return [
                        'indice' => $key + 1, // El índice inicia en 0, sumamos 1 para que empiece en 1
                        'transaccionId' => $transaccion->transaccion_id,
                        'descripcion' => $transaccion->descripcion,
                        'rubroId' => $transaccion->rubro_id,
                        'tipo' => $transaccion->tipo,
                        'montoBruto' => $transaccion->monto_bruto,
                        'montoRetencion' => $transaccion->monto_retencion,
                        'montoComision' => $transaccion->monto_comision,
                        'montoNeto' => $transaccion->monto_neto,
                    ];
                    //   $ultimoUso  =Transaccion::latest($transaccion->rubro_id)->first(),
    
                }),
                //  $ultimoUso  =Transaccion::latest($transaccion->rubro_id)->first();
                // HistorialSaldo

                // --- NUEVA PROPIEDAD: Totales finales como un arreglo con un objeto dentro ---
                'totalesFinales' => [
                    [
                        // 'esTotal' => true,
                        'totalMontoBruto' => $rubro->transacciones->sum('monto_bruto'),
                        'totalMontoImpuesto' => $rubro->transacciones->sum('monto_impuesto'),
                        'totalMontoRetencion' => $rubro->transacciones->sum('monto_retencion'),
                        'totalMontoComision' => $rubro->transacciones->sum('monto_comision'),
                        'totalMontoNeto' => $rubro->transacciones->sum('monto_neto'),
                    ]
                ]
            ];
        });
        return $resultado;
    }


}