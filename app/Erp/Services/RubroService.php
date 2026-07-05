<?php

namespace App\Erp\Services;

use App\Erp\Repositories\Contracts\TransaccionRepositoryInterface;
use App\Erp\Repositories\Eloquent\RubroRepository;
use App\Models\Rubro;
use App\Models\TipoPago;
use Exception;
use Illuminate\Support\Facades\DB;

class RubroService
{
    // protected $rubroRepository;
    public function __construct(private RubroRepository $rubroRepository)
    {
        $this->rubroRepository = $rubroRepository;
    }

    public function getAll()
    {
        return $this->rubroRepository->getAll();
    }

    public function store(array $data): Rubro
    {

        if ($data['tipo'] !== "INGRESO" && $data['tipo'] !== "EGRESO") {
            throw new \InvalidArgumentException('El tipo debe ser INGRESO o EGRESO.');
        }

        // if (empty(($data['is_active']))) {
        //     $data['is_active'] = true;
        //     //  dd("mi activo", $data['is_active']);
        // }
        // dd("la noticia: ",$data);
        return $this->rubroRepository->create($data);

    }




}