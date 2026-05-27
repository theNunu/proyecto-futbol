<?php

namespace App\Services;

use App\Models\News;
use App\Repositories\NewsRepository;
use Carbon\Carbon;
use InvalidArgumentException;

// use App\Repositories\TournamentRepository;
class NewsService
{
    public function __construct(private NewsRepository $repository)
    {

    }

    public function getAll()
    {
        return  $this->repository->getAll();
    }

    public function store(array $data): News
    {
        // 1. Convertir las strings a instancias de Carbon para compararlas
        $inicio = Carbon::parse($data['begin_date']);
        $fin = Carbon::parse($data['end_date']);

        // 2. Aplicar la condicional de negocio
        if ($fin->lessThanOrEqualTo($inicio)) {
            throw new InvalidArgumentException('La fecha de fin debe ser mayor a la fecha de inicio.');
        }
        return $this->repository->create($data);
    
    }

}
