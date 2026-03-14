<?php

namespace App\Repositories;

use App\Models\GameMatch;
use Illuminate\Database\Eloquent\Collection;

class MatchRepository
{

     public function create(array $data): GameMatch
    {
        return GameMatch::create($data);
    }

    public function getByTournament(int $tournamentId): Collection
    {
        return GameMatch::where('tournament_id', $tournamentId)
            ->with(['homeTeam','awayTeam','phase'])
            ->get();
    }

    public function findById(int $matchId): ?GameMatch
    {
        return GameMatch::find($matchId);
    }

    public function update(GameMatch $match, array $data): GameMatch
    {
        $match->update($data);

        return $match;
    }

    public function delete(GameMatch $match): void
    {
        $match->delete();
    }
}
