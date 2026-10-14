<?php

namespace App\Admin\FootballManagment\Repository;

use App\Models\GameMatch;
use Illuminate\Database\Eloquent\Collection;

class MatchRepository
{

     public function create(array $data)
    {
        // dd($data);
        $created = GameMatch::create($data);
        return $created->load('tournament','phase', 'homeTeam', 'awayTeam');
        // return GameMatch::create($data);
    }

    public function getByTournament(int $tournamentId): Collection //esto me lo dio la ia no se por que
    {
        return GameMatch::where('tournament_id', $tournamentId)
            ->with(['homeTeam','awayTeam','phase'])
            ->get();
    }

     public function getById(int $matchId)
    {

     return GameMatch::where('match_id', $matchId)
            ->with(['tournament','phase','homeTeam','awayTeam'])
            ->first();
        // return GameMatch::where('match_id', $matchId)
        //     ->with(['homeTeam','awayTeam','phase'])
        //     ->get();
    }

    public function findById(int $matchId): ?GameMatch
    {
        return GameMatch::find($matchId);
    }

    // public function update(GameMatch $match, array $data): GameMatch
    // {
    //     $match->update($data);

    //     return $match;
    // }

    public function update(GameMatch $match, array $data): GameMatch
    {
        $match->update([
            'tournament_id' => $data['tournament_id'],
            'phase_id' => $data['phase_id'],
            'home_team_id' => $data['home_team_id'],
            'away_team_id' => $data['away_team_id'],
            'home_score' => $data['home_score'],
            'away_score' => $data['away_score'],
            'match_date' => $data['match_date'],
            'status' => $data['status'],
            // 'match_time' => $data['match_time'],
        ]);
        return $match;
    }


    public function delete(GameMatch $match): void
    {
        $match->delete();
    }
}
