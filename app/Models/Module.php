<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';

    protected $primaryKey = 'module_id';

    protected $fillable = [
        'parent_id',
        'name',
        'route',
        'icon',
        'order',
        'is_active'
    ];
    //
    // Relación: Un módulo puede tener muchos hijos
    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id')->orderBy('order', 'asc');
    }

    // Relación: Un módulo pertenece a un padre
    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }
}
