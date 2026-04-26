<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Tournament extends Model
{
    protected $table = 'tournaments';
    protected $primaryKey = 'tournament_id';

    protected $fillable = [
        'name',
        'season'
    ];

    /**
     * A tournament has many phases
     */
    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class, 'tournament_id');
    }

    public function teams()
    {
        return $this->belongsToMany(
            Team::class,
            'tournament_teams',
            'tournament_id',
            'team_id'
        );
    }

    public function seasons(): HasMany
    {
        return $this->hasMany(Season::class, 'tournament_id');
    }
}
