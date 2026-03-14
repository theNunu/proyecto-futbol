<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Phase extends Model
{

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $table = 'phases';
    protected $primaryKey = 'phase_id';

    protected $fillable = [
        'tournament_id',
        'name',
        'order',
        'has_extra_time',
        'has_penalties'
    ];

    /**
     * Phase belongs to a tournament
     */
    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class, 'tournament_id');
    }

    /**
     * Phase has many matches (future)
     */
    public function matches(): HasMany
    {
        return $this->hasMany(GameMatch::class, 'phase_id');
    }
}
