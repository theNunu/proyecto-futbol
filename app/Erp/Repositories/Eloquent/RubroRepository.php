<?php

namespace App\Erp\Repositories\Eloquent;

// use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\Transaccion;
use App\Models\HistorialSaldo;
use App\Models\Rubro;

// use App\Repositories\Contracts\TransaccionRepositoryInterface;

class RubroRepository
{

    public function getAll()
    {
        // 1. Capturamos el parámetro 'nombre' (si no existe, será null)
        return Rubro::all();
    }

    public function create(array $data): Rubro
    {
        //  dd($data);
        return Rubro::create([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipo'],
            'porcentaje_impuesto' => $data['porcentaje_impuesto'],
            'porcentaje_retencion' => $data['porcentaje_retencion'],
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