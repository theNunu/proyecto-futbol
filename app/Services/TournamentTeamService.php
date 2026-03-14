<?php
namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentTeam;
use App\Repositories\TournamentTeamRepository;
use Illuminate\Database\Eloquent\Collection;
// use League\Config\Exception\ValidationException;
use Illuminate\Validation\ValidationException;

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
        $exist = TournamentTeam::where('tournament_id', $data['tournament_id'])->where('team_id', $data['team_id'])->first();;
        if ($exist) {
            throw ValidationException::withMessages([
                'campo_nombre' => 'Este Equipo ya existe en el Torneo seleccionado.',
            ]);
        }

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