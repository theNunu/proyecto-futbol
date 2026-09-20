<?php

namespace App\Admin\Security\Repository;

use App\Models\GameMatch;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;

class RoleRepository
{

    public function getAll()
    {
        return Role::get();
        // return $this->roleRepository->getAll();
    }

     public function store($data)
    {
        // return Role::create[
            
        // ];
        // return $this->roleRepository->getAll();
          // 1. Creamos la noticia
        return  Role::create([
            'name' => $data['name'],
            'key' => $data['key'],
            'is_system' => $data['is_system'] ?? true,
            'is_active' => $data['is_active']?? true,
        ]);
    }
    //  public function create(array $data): GameMatch
    // {
    //     return GameMatch::create($data);
    // }

    // public function getByTournament(int $tournamentId): Collection
    // {
    //     return GameMatch::where('tournament_id', $tournamentId)
    //         ->with(['homeTeam','awayTeam','phase'])
    //         ->get();
    // }

    // public function findById(int $matchId): ?GameMatch
    // {
    //     return GameMatch::find($matchId);
    // }

    // public function update(GameMatch $match, array $data): GameMatch
    // {
    //     $match->update($data);

    //     return $match;
    // }

    // public function delete(GameMatch $match): void
    // {
    //     $match->delete();
    // }
}
