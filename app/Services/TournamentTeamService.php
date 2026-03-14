<?php
namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Repositories\TournamentTeamRepository;
use Illuminate\Database\Eloquent\Collection;

class TournamentTeamService
{
    public function __construct(private TournamentTeamRepository $repository) 
    {
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

      public function getTeams($tournamentId)
    {
        $to = Tournament::with('teams')->where('tournament_id', $tournamentId)->get();
        return $to;
        // return $this->repository->getTeams();
    }

    public function addTeam(array $data): TournamentTeam
    {
        return $this->repository->create($data);
    }

    public function listTeams(int $tournamentId): Collection
    {
        return $this->repository->getByTournament($tournamentId);
    }

    public function removeTeam(TournamentTeam $tournamentTeam): void
    {
        $this->repository->delete($tournamentTeam);
    }
}