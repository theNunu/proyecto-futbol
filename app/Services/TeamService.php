<?php

namespace App\Services;

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class TeamService
{
    public function __construct(
        private TeamRepository $repository
    ) {
    }

    public function list(): Collection
    {
        return $this->repository->getAll();
    }

    public function store(array $data): Team
    {
        return $this->repository->create($data);
    }

    public function update($teamId, array $data): array
    {
        if (!ctype_digit($teamId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $team = $this->repository->findById($teamId);

        if (!$team) {
            throw new NotFoundHttpException('ID del Equipo no encontrada.');
        }
        $result = $this->repository->update($team, $data);

        return $result->only([
            'tournament_id',
            'name',
            'season'
        ]);
        // return $this->repository->update($team, $data);
    }

    public function delete(Team $team): void
    {
        $this->repository->delete($team);
    }
}
