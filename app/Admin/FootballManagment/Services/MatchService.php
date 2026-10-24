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
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
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
        // 1. Verificar existencia
        $gameMatch = $this->matchRepository->getById($matchId);
        if (!$gameMatch) {
            throw new NotFoundHttpException('ID de partido no encontrada.');
        }

        $estadoActual = $gameMatch->status; // Estado en la BD antes del cambio
        //   dd('eyd');
        // 2. Aplicar validación estricta según el estado actual
        $this->validarReglasPorEstado($estadoActual, $data);

        // 3. Resolver el problema que planteas: Si vuelve de 'live' a 'scheduled', reseteamos los goles
        if ($estadoActual === 'live' && isset($data['status']) && $data['status'] === 'scheduled') {
            /*
            • ¿Qué hace en total? Si el partido estaba en vivo, pero el usuario decidió regresarlo a programado,
            el sistema dice: "Ok, te permito regresarlo, pero yo mismo voy a meter a la fuerza en la maleta ($data) 
            que los goles de casa (home_score) y visita (away_score) vuelvan a ser 0". 
            Así se limpia el marcador automáticamente.¿Lograste ver más claro el funcionamiento del foreach con la anal
            */
            $data['home_score'] = 0;
            $data['away_score'] = 0;
        }

        if ($estadoActual === 'live' && isset($data['status']) && $data['status'] === 'finished') {

            $winner = $data['home_score'] > $data['away_score'] ? $gameMatch->homeTeam : $gameMatch->awayTeam;

            $data['winner_team_id'] = $winner->team_id;
            // dd("that tilin", $gameMatch->home_score, $gameMatch->away_score, $gameMatch, $gameMatch->awayTeam->team_id, $gameMatch->homeTeam->team_id);
        }

        // 4. Guardar los datos (ya validados y limpios)
        return $this->matchRepository->update($gameMatch, $data);
    }

    /**
     * Valida estrictamente qué campos se permiten enviar según el estado actual del partido.
     */
    private function validarReglasPorEstado(string $estadoActual, array $data): void
    {
        // REGLA 3: Si ya está finalizado, no se permite enviar NADA para actualizar
        if ($estadoActual === 'finished') {
            throw new UnprocessableEntityHttpException("El partido ya está finalizado y no se puede modificar.");
        }

        // REGLA 4: Si está en vivo, SOLO se permite actualizar home_score, away_score y status
        if ($estadoActual === 'live') {

            $camposPermitidos = ['home_score', 'away_score', 'status'];
            // dd('eyy');
            // Revisamos si el usuario envió algún campo que NO esté en la lista de permitidos
            foreach ($data as $key => $value) {
                //   dd("ojo", $key,$value);
                if (!in_array($key, $camposPermitidos)) {
                    throw new UnprocessableEntityHttpException(
                        "No puedes actualizar el campo '{$key}' mientras el partido esté EN VIVO."
                    );
                }
            }
        }

        // REGLA 2: Si está programado, se permite editar sus campos operativos.
        if ($estadoActual === 'scheduled') {

            $camposPermitidos = ['tournament_id', 'phase_id', 'home_team_id', 'away_team_id', 'match_date', 'status'];

            foreach ($data as $key => $value) {
                // Si envían 'home_score' o 'away_score' en 'scheduled', los rebotamos para evitar inconsistencias
                if (!in_array($key, $camposPermitidos)) {
                    throw new UnprocessableEntityHttpException(
                        "No puedes modificar el campo '{$key}' en un partido PROGRAMADO."
                    );
                }
            }
        }

    }
    public function delete(GameMatch $match): void
    {
        $this->matchRepository->delete($match);
    }
}
