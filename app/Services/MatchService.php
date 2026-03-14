<?php

namespace App\Services;

use App\Models\GameMatch;
use App\Models\Phase;
use App\Models\Tournament;
use App\Repositories\MatchRepository;
use App\Repositories\PhaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class MatchService
{
    public function __construct(
        private MatchRepository $repository
    ) {
    }

    public function create(array $data): GameMatch
    {
        return $this->repository->create($data);
    }

    public function listByTournament(int $tournamentId): Collection
    {
        return $this->repository->getByTournament($tournamentId);
    }

    public function update(GameMatch $match, array $data): GameMatch
    {
        return $this->repository->update($match, $data);
    }

    public function delete(GameMatch $match): void
    {
        $this->repository->delete($match);
    }
}
