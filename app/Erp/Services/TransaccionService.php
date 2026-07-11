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


        // Log::info('todos los rubros', [$los]);

        // $newObject = [
        //     'fecha_conexion' => now()->toDateTimeString(),
        //     'dispositivo' => 'Móvil',
        // ];
        // $allRubros[] = $newObject;
        // dd("lass tranacciones", $userRow);

        // $myRubro = [];
        $rubrosParaComparar = [];

        foreach ($los as $item) { //obtner transacciones con sus rubros para validacion

            // 2. Ahora sí, cada $item contiene la llave 'children'
            foreach ($item['children'] as $transaccion) {

                // 3. Guardas el rubro_id usando corchetes
                $rubrosParaComparar[] = $transaccion['rubro_id'];
            }
        }
        // dd($rubrosParaComparar);
        $resultado = collect($rubrosParaComparar)->unique()->values()->all();

        // dd($resultado); // Esto te devolverá: [1, 2, 3]

        $rubroConTransacciones = [];
        $rubrosDeTransaccion = [];
        // dd($allRubros);
        $losRubros = [];
        foreach ($allRubros as $myRubro) {
            // $temporalRubro = $myRubro[0];
// dd($temporalRubro);
            $myRubro->rubro_id;
            // dd($los);
            Log::info('Usuario procesado con éxito', ['el rubro join' => $myRubro]);
            // dd($myRubro, $los, $myRubro[0], $myRubro, $temporalRubro ,$temporalRubro->rubro_id);
            // dd($myRubro[0]);
            foreach ($resultado as $rubroIndividual) {
                // dd("rubro individual : ",$rubroIndividual);
                $rubroConsuTrans = Transaccion::where('rubro_id', $rubroIndividual)->get();
                // $rubroConsuTrans = Transaccion::firstwhere('rubro_id', $rubroIndividual);
                // $rubroConsuTrans =  Transaccion::where('rubro_id', $)
                Log::info('Usuario procesado con éxito', ['el rubro individual' => $rubroIndividual]);
                if ($myRubro->rubro_id == $rubroIndividual) {
                    $rubrosDeTransaccion[] = $rubroConsuTrans;

                }

            }

            $losRubros[] = $rubrosDeTransaccion;
            // 'fecha_conexion' => now()->toDateTimeString(),
            // 'dispositivo' => 'Móvil',
            $rubroConTransacciones[] = $losRubros;
            // $allRubros[] = $newObject;
        }
        // return $rubrosParaComparar;
        // dd($rubrosParaComparar, $rubrosDeTransaccion);
        // $diddy = Transaccion::where('rubro_id', 2)->get();
        // dd($rubroConTransacciones);
        return [
            $allRubros,
            // "rubro existe " => $rubrosExistentes,
            // "extraterrestres" => $los,
            "rubro con transacciones" => $losRubros
            // "cada transaccion" => $userRow
        ];
        // return $users;

    }
}