<?php

namespace App\Services;

use App\Models\Tournament;
use App\Repositories\TournamentRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class TournamentService
{
    public function __construct(
        private TournamentRepository $repository
    ) {}

    public function list(): Collection
    {
        return $this->repository->getAll();
    }

    public function get($id)
    {
        if (!ctype_digit($id)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $tournmanet = $this->repository->findById($id);

        if(!$tournmanet){
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }

        return $this->repository->findById($id);
    }

    public function store(array $data): Tournament
    {
        return $this->repository->create($data);
    }

    public function update(Tournament $tournament, array $data): Tournament
    {
        return $this->repository->update($tournament, $data);
    }

    public function delete(Tournament $tournament): void
    {
        $this->repository->delete($tournament);
    }
}
