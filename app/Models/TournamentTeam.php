<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TournamentTeam extends Model
{
    protected $table = 'tournament_teams';

    protected $primaryKey = 'tournament_team_id';

    protected $fillable = [
        'tournament_id',
        'team_id'
    ];

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(
            Tournament::class,
            'tournament_id'
        );
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(
            Team::class,
            'team_id'
        );
    }
}