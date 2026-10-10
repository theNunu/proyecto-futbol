<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository
{
    public function getAll()
    {
        // return Team::all();
        return Team::select('team_id', 'name','short_name', 'logo_path')->get();
    }

    public function findById($teamId): ?Team
    {
        return Team::find($teamId);
    }

    public function create(array $data): Team
    {
        // dd('----');
        return Team::create([
            'name' => $data['name'],
            'short_name' => $data['short_name'] ?? null,
            'logo_path' => $data['logo_path'] ??null
        ]);
    }

    public function update(Team $team, array $data): Team
    {
        $team->update([
            'name' => $data['name'],
            'short_name' => $data['short_name'],
            'logo_path' => $data['logo_path']
        ]);
        return $team;
    }

    public function delete(Team $team): void
    {
        $team->delete();
    }
}
