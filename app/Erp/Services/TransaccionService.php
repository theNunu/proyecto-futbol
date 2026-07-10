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
        $results = DB::table('rubros')
            ->join('transaccions', 'transaccions.rubro_id', '=', 'rubros.rubro_id')
            ->select('rubros.rubro_id', 'rubros.nombre', 'transaccions.descripcion', 'transaccions.transaccion_id')
            ->groupBy('rubros.rubro_id', 'rubros.nombre', 'transaccions.descripcion', 'transaccions.transaccion_id')
            ->get();

        // return $results;
        // $transacciones = Transaccion::where('rubro_id')->get();

        $trans = DB::table('transaccions')
            ->join('rubros', 'rubros.rubro_id', '=', 'transaccions.rubro_id')
            ->get();
        $myTransaccion = [];
        // dd($trans);
        foreach ($trans as $userId => $userRow) {

            // El conteo se saca contando los elementos agrupados en la colección
            // $totalPosts = $userRow->count();
            // $totalPosts = collect($userRow); 
            // $userName = $userRow->first()->user_name;
            //  dd($userCollection);
            $userCollection = collect($userRow);
            // $userName = "la transaccion: ";
            // dd($userRow);

            foreach ($userRow as $row) {
                //  dd($userRow, "la row:", $row);
                // echo "- Post: " . $row->descripcion . "<br>";
                $myTransaccion = $row;
            }


            // foreach ($userRow as $row) {
            //     echo "- Post: " . $row->title . "<br>";
            // }
        }

        // dd("lass tranacciones", $userRow);
        return [
            $results,
            "cada transaccion" => $userRow
        ];
        // return $users;

    }
}