<?php

namespace App\Erp\Repositories\Eloquent;

// use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\TipoPago;

// use App\Repositories\Contracts\TransaccionRepositoryInterface;

class TipoPagoRepository
{

    public function getAll()
    {
        // 1. Capturamos el parámetro 'nombre' (si no existe, será null)
        return TipoPago::all();
    }

    public function create(array $data): TipoPago
    {
        //  dd($data);
        return TipoPago::create([
            'nombre' => $data['nombre'],
            'porcentaje_comision' => $data['porcentaje_comision'],
            // 'porcentaje_impuesto' => $data['porcentaje_impuesto'],
            // 'porcentaje_retencion' => $data['porcentaje_retencion'],
        ]);
    }


    // public function obtenerUltimoSaldo(): float
    // {
    //     // Buscamos el último registro en el libro mayor
    //     $ultimoHistorial = HistorialSaldo::latest('historial_saldo_id')->first();

    //     // Si no hay transacciones previas, el saldo inicial es 0.00
    //     return $ultimoHistorial ? $ultimoHistorial->saldo_posterior : 0.00;
    // }

    // public function guardarTransaccion(array $datosTransaccion): Transaccion
    // {
    //     return Transaccion::create($datosTransaccion);
    // }

    // public function registrarHistorialSaldo(array $datosSaldo): HistorialSaldo
    // {
    //     return HistorialSaldo::create($datosSaldo);
    // }
}