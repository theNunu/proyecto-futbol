<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $table = 'teams';
    protected $primaryKey = 'team_id';

    protected $fillable = [
        'name',
        'short_name',
        'logo_path'
    ];

    /**
     * Team has many players
     */
    // public function players(): HasMany
    // {
    //     return $this->hasMany(Player::class, 'team_id');
    // }

    /**
     * Team participates in many tournaments
     * (future pivot table: tournament_teams)
     */
    public function tournaments(): BelongsToMany
    {
        return $this->belongsToMany(
            Tournament::class,
            'tournament_teams',
            'team_id',
            'tournament_id'
        );
    }

    /**
     * Matches where the team is local
     */
    // public function homeMatches(): HasMany
    // {
    //     return $this->hasMany(Match::class, 'home_team_id');
    // }

    /**
     * Matches where the team is away
     */
    // public function awayMatches(): HasMany
    // {
    //     return $this->hasMany(Match::class, 'away_team_id');
    // }
}
