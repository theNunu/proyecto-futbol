<?php

namespace App\Repositories;

use App\Models\Season;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Collection;

class SeasonRepository
{
    public function getAll(): Collection
    {
        return Season::all();
    }

    public function findById(int $seasonId): ?Season
    {
        return Season::where('season_id', $seasonId)->first();
    }

    public function create(array $data): Season
    {
        return Season::create([
            'name' => $data['name'],
            'active' => $data['active'] ?? true,
        ]);
        // return Tournament::create($data);
    }

    public function update(Season $season, array $data): Season
    {
        $season->update([
            'name' => $data['name'],
            'active' => $data['active'],
        ]);
        return $season;
    }

    public function delete(Season $season): void
    {
        $season->delete();
    }

     public function deactivate(Season $season)
    {
        // Alternar el valor (si es true pasa a false, y viceversa)
        $season->active = !$season->active;
        return $season->save();
    }
}
