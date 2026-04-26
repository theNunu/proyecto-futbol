<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Menu extends Model
{
    //
    protected $table = 'menus';

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'url',
        'order',
        'is_active'
    ];

    /**
     * Obtiene los hijos directos de este menú.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    /**
     * Obtiene los hijos de forma recursiva (hijos, nietos, etc.)
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Obtiene el padre de este menú.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

}
