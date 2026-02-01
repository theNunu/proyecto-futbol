<?php

namespace App\Services;

use App\Models\Tournament;
use App\Repositories\TournamentRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class TournamentService
{
    public function __construct(private TournamentRepository $repository)
    {

    }

    public function list()
    {
        $tournaments = $this->repository->getAll();
        return $tournaments->map(function ($t) {
            return [
                'tournament_id' => $t->tournament_id,
                'name' => $t->name,
                'season' => $t->season
            ];
        });
    }
    // api respponse en los request para ser llamadados en los controladores

    public function get($id): array
    {
        if (!ctype_digit($id)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $tournament = $this->repository->findById($id);

        if (!$tournament) {
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }

        return [
            'tournament_id' => $tournament->tournament_id,
            'name' => $tournament->name,
            'season' => $tournament->season
        ];

    }

    public function store(array $data): array
    {
        $tournament = $this->repository->create($data);

        return $tournament->only([
            'tournament_id',
            'name',
            'season'
        ]);
    }

    public function update($tournamentId, array $data): Tournament
    {
        if (!ctype_digit($tournamentId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $tournmanet = $this->repository->findById($tournamentId);

        if (!$tournmanet) {
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }
        return $this->repository->update($tournmanet, $data);

        // return $this->repository->update($tournamentId, $data);


        // return $this->repository->update($tournament, $data);
    }

    public function delete(Tournament $tournament): void
    {
        $this->repository->delete($tournament);
    }
    /*
        public function deactivate($roleId)
    {
        $role = $this->roleRepository->find($roleId);

        if (!$role) {
            throw new NotFoundHttpException('Rol no encontrado.');
        }

        return $this->roleRepository->deactivate($role);
    }
    */

}
