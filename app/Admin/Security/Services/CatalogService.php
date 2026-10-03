<?php

namespace App\Services;

use App\Models\Catalog;
use App\Models\CatalogDetail;
use App\Models\Tournament;
use App\Repositories\CatalogRepository;
// use App\Repositories\TournamentRepository;
class CatalogService
{
    public function __construct(private CatalogRepository $repository)
    {

    }

    public function getAll()
    {
        return  $this->repository->getAll();
    }

    public function store(array $data): Catalog
    {
        return $this->repository->create($data);
    
    }

    public function update($tournamentId, array $data)
    {
        // if (!ctype_digit($tournamentId)) {
        //     throw new \InvalidArgumentException('El ID debe ser un número entero.');
        // }

        // $tournament = $this->repository->findById($tournamentId);

        // if (!$tournament) {
        //     throw new NotFoundHttpException('ID del Torneo no encontrada.');
        // }
        // $result = $this->repository->update($tournament, $data);

        // return $result->only([
        //     'tournament_id',
        //     'name',
        //     'season'
        // ]);
    }

    public function delete(Tournament $tournament): void
    {
    //     $this->repository->delete($tournament);
    }

    public function storeDetail(array $data)
    {
        return $this->repository->createDetail($data);
    }

    public function getAllDetailsWithCatalog(){
        // dd('didy');
        // return CatalogDetail::with('catalog')->get();
        return Catalog::with('catalog_details')->get();
    }



}
