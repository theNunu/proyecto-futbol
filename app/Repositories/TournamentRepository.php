<?php

namespace App\Repositories;

use App\Models\Tournament;
use Illuminate\Database\Eloquent\Collection;

class TournamentRepository
{
    public function getAll(): Collection
    {
        return Tournament::all();
    }

    public function findById(int $tournamentId): ?Tournament
    {
        return Tournament::where('tournament_id', $tournamentId)->first();
    }

    public function create(array $data): Tournament
    {
        return Tournament::create([
            'name'     => $data['name'],
            'season'   => $data['season'],
            'season_id'   => $data['season_id'],
        ]);
        // return Tournament::create($data);
    }

    public function update(Tournament $tournament, array $data): Tournament
    {
        $tournament->update([
            'name'     => $data['name'],
            'season'   => $data['season'],
            'season_id'   => $data['season_id'],
        ]);
        return $tournament;
    }

    public function delete(Tournament $tournament): void
    {
        $tournament->delete();
    }

    /*
        public function deactivate(Role $role)
    {
        $role->is_active = false;
        $role->updated_by = $this->userIdAccion;
        $role->save();
        return $role;
    }
    
    
    */
}
