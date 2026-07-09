<?php

namespace App\Erp\Services;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Models\Rubro;
use App\Models\TipoPago;
use App\Models\Transaccion;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $users = DB::table('rubros')
            ->join('transaccions', 'transaccions.rubro_id', '=', 'rubros.rubro_id')
            ->select('rubros.rubro_id','rubros.nombre', 'transaccions.descripcion', DB::raw('COUNT(transaccions.transaccion_id) as total_transacciones'))
            ->groupBy('rubros.rubro_id','rubros.nombre','transaccions.descripcion')
            ->get();

        return $users;

    }
}