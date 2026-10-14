<?php

namespace App\Admin\FootballManagment\Services;

use App\Admin\FootballManagment\Repository\MatchRepository;
use App\Models\GameMatch;
use App\Models\Phase;
use App\Models\Tournament;
// use App\Repositories\MatchRepository;
use App\Repositories\PhaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use InvalidArgumentException;
class MatchService
{
    public function __construct(
        private MatchRepository $matchRepository
    ) {
    }

    public function create(array $data)
    {

        $data['status'] = 'scheduled'; // valor por defecto al momento de crear un partido
        return $this->matchRepository->create($data);
    }

    public function getById(int $gameMatchId)
    {
        $gameMatch = $this->matchRepository->getById($gameMatchId);

        if (!$gameMatch) {
            throw new NotFoundHttpException('ID de partido no encontrada.');
        }

        return $gameMatch;


    }


    public function listByTournament(int $tournamentId): Collection
    {
        return $this->matchRepository->getByTournament($tournamentId);
    }

    /*
     REGLAS PARA LA CREACION Y UPDATE DE PARTIDOS
     1. los partidos se crean por defecto en estado "programado" (scheduled)
     2. cuando un partido esta en estado "programado" se puede editar : (tournament_id, phase_id, home_team_id, away_team_id, match_date, status)
     3. una vez cambiado el estado a "finished" ya no se puede editar nada ni siquiera el estado
     4. cuando el partido esta en estado "live" solo se puede editar (home_score, away_score, status)
     5. se pude cambiar de estado programado a en vivo y viceversa

    */

     /*
     en caso de  haber creado (tournament_id, phase_id, home_team_id, away_team_id, match_date, status) y luego cambias a live y editas 
     (home_away, away_score, status)pero luego vas a devolver el estado a programado  lo actualiado (home_score, away_socre, status)
     se deschace  porque al ser otravez estado programdo puedes cambiar los equipos y quedaria raro

     */

    public function update(array $data, int $matchId)
    {

        $gameMatch = $this->matchRepository->getById($matchId);
        // dd($gameMatch);
        if (!$gameMatch) {
            throw new NotFoundHttpException('ID de partido no encontrada.');
        }
        // 2. Filtrar los campos permitidos según el estado actual
        $camposPermitidos = match ($gameMatch->status) {
            'scheduled' => $this->filtrarParaProgramado($data),
            'live' => $this->filtrarParaEnVivo($data),
            'finished' => $this->filtrarParaFinalizado($data),
            default => throw new \InvalidArgumentException("Estado del evento no válido."),
        };

        // 3. Si no hay campos que se puedan actualizar, lanzar excepción o retornar
        if (empty($camposPermitidos)) {
            throw new \InvalidArgumentException("No se permiten modificar campos en el estado actual.");
        }

        dd('bloquear', $data);
        $result = $this->matchRepository->update($gameMatch, $data);

        return $result;
        // return $this->matchRepository->update($match, $data);
    }

    private function filtrarParaProgramado(array $data): array
    {
        // En 'programado' se puede editar todo (ej: fecha, título, descripción, estado)
        return $data;
    }

    private function filtrarParaEnVivo(array $data): array
    {
        // $invertido = array_flip($data);
        // dd('eso', $data, $invertido);
        // En 'en vivo' SOLO permitimos cambiar la descripción o pasar a 'finalizado'
        // Ignoramos fechas o títulos si vienen en la petición

        /*
        1. array_flip(['descripcion', 'estado']): Transforma un arreglo simple en uno donde los textos se vuelven las
        "llaves" (keys). Pasa de [0 => 'descripcion', 1 => 'estado'] a ['descripcion' => 0, 'estado' => 1].
         */

        /*
        2. array_intersect_key($data, ...): Compara los datos que envió el usuario 
        ($data) contra nuestro arreglo volteado. Solo conserva los elementos cuyas llaves existan en ambos arreglos.
        */
        return array_intersect_key($data, array_flip(['home_score', 'away_score', 'status', 'match_date']));
    }

    private function filtrarParaFinalizado(array $data): array
    {
        // En 'finalizado' ya NO se puede cambiar nada
        return [];
    }

    public function delete(GameMatch $match): void
    {
        $this->matchRepository->delete($match);
    }
}
