<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameMatch extends Model
{
    protected $table = 'match_games';

    protected $primaryKey = 'match_id';

    protected $fillable = [
        'tournament_id',
        'phase_id',
        'home_team_id',
        'away_team_id',
        'home_score',
        'away_score',
        'winner_team_id',
        'match_date',
        'status'
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(
            Tournament::class,
            'tournament_id'
        );
    }

    public function phase(): BelongsTo
    {
        return $this->belongsTo(
            Phase::class,
            'phase_id'
        );
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(
            Team::class,
            'home_team_id'
        );
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(
            Team::class,
            'away_team_id'
        );
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(
            Team::class,
            'winner_team_id'
        );
    }

}
