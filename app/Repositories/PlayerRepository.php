<?php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerRepository
{
    public function getAll(Request $request)
    {
        $searchName = $request->query('name');
        $filtrarEstado = $request->query('is_active');

        return Player::query()
            ->when($searchName, function ($query, $nombre) {
                return $query->where('name', 'LIKE', '%'.$nombre.'%');
            })
            ->when($request->filled('is_active'), function ($query) use ($filtrarEstado) {
                $valorBooleano = filter_var($filtrarEstado, FILTER_VALIDATE_BOOLEAN);

                return $query->where('is_active', $valorBooleano);
            })
            ->with('files')
            ->get();
    }

    public function create(array $data): Player
    {
        $player = Player::create([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'file_id' => $data['file_id'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        return $player->load('files');
    }

    public function findById(string $playerId): ?Player
    {
        return Player::where('player_id', $playerId)->first();
    }

    public function update(Player $player, array $data): Player
    {
        $player->update([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'birth_date' => $data['birth_date'],
            'file_id' => $data['file_id'] ?? $player->file_id,
            'is_active' => $data['is_active'] ?? $player->is_active,
        ]);

        return $player->load('files');
    }

    public function delete(Player $player): void
    {
        $player->delete();
    }
}
