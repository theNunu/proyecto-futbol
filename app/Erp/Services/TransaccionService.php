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
        $results = DB::table('rubros')
            ->join('transaccions', 'rubros.rubro_id', '=', 'transaccions.rubro_id')
            ->select('rubros.rubro_id', 'rubros.nombre', DB::raw('COUNT(transaccions.transaccion_id) as total_transacciones'))
            ->groupBy('rubros.rubro_id', 'rubros.nombre')
            ->get();
            
        $rubrosExistentes = $results->pluck('rubro_id');
        // dd($rubrosExistentes);
        $los =  [];
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
            // $rubro = [];
            // $rubroId = null;

        }


        Log::info('todos los rubros', [$los]);
        // return $results;
        // $transacciones = Transaccion::where('rubro_id')->get();

        // $trans = DB::table('transaccions')
        //     ->join('rubros', 'rubros.rubro_id', '=', 'transaccions.rubro_id')
        //     ->get();
        // $myTransaccion = [];
        // // dd($trans);
        // foreach ($trans as $userId => $userRow) {

        //     // El conteo se saca contando los elementos agrupados en la colección
        //     // $totalPosts = $userRow->count();
        //     // $totalPosts = collect($userRow); 
        //     // $userName = $userRow->first()->user_name;
        //     //  dd($userCollection);
        //     $userCollection = collect($userRow);
        //     // $userName = "la transaccion: ";
        //     // dd($userRow);

        //     foreach ($userRow as $row) {
        //         //  dd($userRow, "la row:", $row);
        //         // echo "- Post: " . $row->descripcion . "<br>";
        //         $myTransaccion = $row;
        //     }


        //     // foreach ($userRow as $row) {
        //     //     echo "- Post: " . $row->title . "<br>";
        //     // }
        // }
// El arreglo completo debe abrirse con [ y cerrarse con ]
        // $datos = [
        //     "children" => $this->rubros->transacciones->map(function ($transaccion) {
        //         return [
        //             'id' => $transaccion->id,
        //             'monto' => $transaccion->monto, // Cambia por tus columnas reales
        //             'descripcion' => $transaccion->descripcion,
        //             'created_at' => $transaccion->created_at,
        //             'updated_at' => $transaccion->updated_at,
        //         ];
        //     })
        // ];

        //       // 2. Aquí inyectamos cada transacción como si fuera su "hijo" (children)
        //         'children' => $this->transacciones->map(function ($transaccion) {
        //             return [
        //                 'id' => $transaccion->id,
        //                 'monto' => $transaccion->monto, // Cambia por tus columnas reales
        //                 'descripcion' => $transaccion->descripcion,
        //                 'created_at' => $transaccion->created_at,
        //                 'updated_at' => $transaccion->updated_at,
        //          ];
        //         }),
        //     ;
        // }
        $newObject = [
            'fecha_conexion' => now()->toDateTimeString(),
            'dispositivo' => 'Móvil',
        ];
        $results[] = $newObject;
        // dd("lass tranacciones", $userRow);
        return [
            $results,
            "rubro existe " => $rubrosExistentes,
            "extraterrestres" => $los
            // "cada transaccion" => $userRow
        ];
        // return $users;

    }
}