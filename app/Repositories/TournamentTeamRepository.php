<?php

namespace App\Repositories;

use App\Models\TournamentTeam;
use Illuminate\Database\Eloquent\Collection;

class TournamentTeamRepository
{
    public function create(array $data)
    {
        // return TournamentTeam::create($data);
        return TournamentTeam::create([
            'tournament_id'     => $data['tournament_id'],
            'team_id'   => $data['team_id'],
        ]);
    }

    public function getAll(){
        return TournamentTeam::with('team')->get();
    }

    public function getByTournament(int $tournamentId): Collection
    {
        return TournamentTeam::where('tournament_id', $tournamentId)->with('team')->get();
    }

    public function delete(TournamentTeam $tournamentTeam): void
    {
        $tournamentTeam->delete();
    }
}