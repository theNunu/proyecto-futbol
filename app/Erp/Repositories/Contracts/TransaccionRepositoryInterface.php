<?php

namespace App\Erp\Repositories\Contracts;

use App\Models\Transaccion;
use App\Models\HistorialSaldo;

interface TransaccionRepositoryInterface
{
    public function obtenerUltimoSaldo(): float;
    public function guardarTransaccion(array $datosTransaccion): Transaccion;
    public function registrarHistorialSaldo(array $datosSaldo): HistorialSaldo;
}