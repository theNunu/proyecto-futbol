<?php

namespace App\Services;

use App\Models\Phase;
use App\Models\Tournament;
use App\Repositories\PhaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class PhaseService
{
    public function __construct(
        private PhaseRepository $repository
    ) {
    }
    public function getAll()
    {
        $tournaments = $this->repository->getAll();
        return $tournaments->map(function ($t) {
            return [
                'tournament_id' => $t->tournament_id,
                'phases' => $t->name,
                'season' => $t->season,
                'phase' => $t->phases->map(function ($p) {
                    return [
                        'phase_id' => $p->phase_id,
                        'name' => $p->name,
                        "order" => $p->order,
                        "has_extra_time" => $p->has_extra_time,
                        "has_penalties" => $p->has_penalties
                    ];
                })
            ];
        });
    }

    public function getPhasesByTournamentId($tournamentId)
    {
        if (!ctype_digit($tournamentId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }
        $tournament = Tournament::where('tournament_id', $tournamentId)->first();

        if (!$tournament) {
            throw new NotFoundHttpException('ID del Torneo no encontrada.');
        }

        $phases = Phase::where('tournament_id', $tournament->tournament_id)
            ->orderBy('order')->get();
        return [
            "tournament" => $tournament,
            "phases" => $phases
        ];
    }

    public function store(array $data): array
    {
        // 1️⃣ Crear la nueva fase
        $phase = $this->repository->create($data);

        $phase->load('tournament.phases');

        return [
            'tournament' => $phase->tournament
        ];
    }

    public function update($tournamentId, array $data): Phase
    {
        if (!ctype_digit($tournamentId)) {
            throw new \InvalidArgumentException('El ID debe ser un número entero.');
        }

        $phase = $this->repository->findById($tournamentId);

        if (!$phase) {
            throw new NotFoundHttpException('ID de la Fase no encontrada.');
        }
        $result = $this->repository->update($phase, $data);

        return $result->load('tournament');
    }

    public function delete(Phase $phase): void
    {
        $this->repository->delete($phase);
    }
}
