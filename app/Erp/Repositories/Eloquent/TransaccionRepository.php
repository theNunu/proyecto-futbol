<?php

namespace App\Erp\Repositories\Eloquent;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\Transaccion;
use App\Models\HistorialSaldo;
// use SimplePie\Cache\DB;
use Illuminate\Support\Facades\DB;
// use App\Repositories\Contracts\TransaccionRepositoryInterface;

class TransaccionRepository implements TransaccionRepositoryInterface
{
    public function obtenerUltimoSaldo(): float
    {
        // Buscamos el último registro en el libro mayor
        $ultimoHistorial = HistorialSaldo::latest('historial_saldo_id')->first();
        
        // Si no hay transacciones previas, el saldo inicial es 0.00
        return $ultimoHistorial ? $ultimoHistorial->saldo_posterior : 0.00;
    }

    public function guardarTransaccion(array $datosTransaccion): Transaccion
    {
        return Transaccion::create($datosTransaccion);
    }

    public function registrarHistorialSaldo(array $datosSaldo): HistorialSaldo
    {
        return HistorialSaldo::create($datosSaldo);
    }

       public function buscarEnBaseDeDatos(?int $id = null): array
    {
        // Ejecutamos la consulta pura a PostgreSQL
        return DB::select('SELECT * FROM sp_obtener_rubro(?)', [$id]);
    }
}