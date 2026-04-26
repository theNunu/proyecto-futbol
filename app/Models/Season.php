<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Season extends Model
{
    protected $table = 'seasons';
    protected $primaryKey = 'season_id';

    protected $fillable = [
        'name',
        'active',
    ];

    /**
     * Phase belongs to a tournament
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

}
