<?php

namespace App\Erp\Services;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\Rubro;
use App\Models\TipoPago;
// use App\Repositories\Contracts\TransaccionRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class FinancieroService
{
    protected $repository;

    public function __construct(TransaccionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function procesarTransaccion(array $dataCruda)
    {
        // Iniciamos una transacción de BD para asegurar atomicidad (Todo o nada)
        return DB::transaction(function () use ($dataCruda) {
            
            // 1. Obtener las entidades para aplicar sus porcentajes de configuración
            $rubro = Rubro::findOrFail($dataCruda['rubro_id']);
            $tipoPago = TipoPago::findOrFail($dataCruda['tipo_pago_id']);
            
            $montoBruto = (float) $dataCruda['monto_bruto'];

            // 2. Cálculos financieros utilizando arrays/variables en memoria
            $impuesto  = $montoBruto * ($rubro->porcentaje_impuesto / 100);
            $retencion = $montoBruto * ($rubro->porcentaje_retencion / 100);
            $comision  = $montoBruto * ($tipoPago->porcentaje_comision / 100);

            // Determinar el neto real que entra o sale según el tipo de flujo financiero
            // Si es Ingreso/Abono: El impuesto suma, pero la retención de la fuente y la comisión bancaria restan de tu neto recibido
            if ($dataCruda['tipo'] === 'ingreso' || $dataCruda['tipo'] === 'abono') {
                $montoNeto = $montoBruto + $impuesto - $retencion - $comision;
                $movimientoSaldo = $montoNeto; // Sumará al saldo general
            } else {
                // Si es Egreso: Pagas el bruto más el impuesto que te cobran, menos lo que tú le retienes a tu proveedor
                $montoNeto = $montoBruto + $impuesto - $retencion;
                $movimientoSaldo = -$montoNeto; // Restará del saldo general
            }

            // 3. Preparar el array estructurado para persistir la transacción
            $payloadTransaccion = array_merge($dataCruda, [
                'monto_impuesto'  => round($impuesto, 2),
                'monto_retencion' => round($retencion, 2),
                'monto_comision'  => round($comision, 2),
                'monto_neto'       => round($montoNeto, 2)
            ]);

            $transaccionGuardada = $this->repository->guardarTransaccion($payloadTransaccion);

            // 4. Lógica del Libro Mayor (Ledger) para control de saldos y auditoría
            $saldoAnterior = $this->repository->obtenerUltimoSaldo();
            $saldoPosterior = $saldoAnterior + $movimientoSaldo;

            // Preparar el array del historial de saldo
            $payloadSaldo = [
                'transaccion_id'  => $transaccionGuardada->transaccion_id,
                'saldo_anterior'  => round($saldoAnterior, 2),
                'monto_movimiento'=> round($movimientoSaldo, 2),
                'saldo_posterior' => round($saldoPosterior, 2)
            ];

            $this->repository->registrarHistorialSaldo($payloadSaldo);

            return $transaccionGuardada->load(['rubro', 'tipoPago', 'historialSaldo']);
        });
    }
}