<?php

namespace App\Repositories;

use App\Models\Phase;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Collection;

class PhaseRepository
{

    public function getAll()
    {
        return Tournament::with('phases')->get();
    }
    public function getByTournament(int $tournamentId): Collection
    {
        return Phase::where('tournament_id', $tournamentId)->orderBy('order')->get();
    }

    public function findById(int $phaseId): ?Phase
    {
        return Phase::find($phaseId);
    }

    public function create(array $data): Phase
    {
        return Phase::create([
            'tournament_id' => $data['tournament_id'],
            'name' => $data['name'],
            'order' => $data['order'],
            'has_extra_time' => $data['has_extra_time'] ?? true,
            'has_penalties' => $data['has_penalties'] ?? true,
        ]);
    }

    public function update(Phase $phase, array $data): Phase
    {
        $phase->update($data);
        return $phase;
    }

    public function delete(Phase $phase): void
    {
        $phase->delete();
    }
}
