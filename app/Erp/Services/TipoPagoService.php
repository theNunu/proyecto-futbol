<?php

namespace App\Erp\Services;

use App\Erp\Repositories\Eloquent\TipoPagoRepository;
use App\Models\Rubro;
use App\Models\TipoPago;
use Exception;
use Illuminate\Support\Facades\DB;

class TipoPagoService
{
    // protected $rubroRepository;
    public function __construct(private TipoPagoRepository $TipoPagoRepository)
    {
        $this->TipoPagoRepository = $TipoPagoRepository;
    }

    public function getAll()
    {
        return $this->TipoPagoRepository->getAll();
    }

    public function store(array $data): TipoPago
    {

        // if ($data['tipo'] !== "INGRESO" && $data['tipo'] !== "EGRESO") {
        //     throw new \InvalidArgumentException('El tipo debe ser INGRESO o EGRESO.');
        // }

        // // if (empty(($data['is_active']))) {
        //     $data['is_active'] = true;
        //     //  dd("mi activo", $data['is_active']);
        // }
        // dd("la noticia: ",$data);
        return $this->TipoPagoRepository->create($data);

    }




}