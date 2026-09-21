<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    //
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $table = 'roles';
    protected $primaryKey = 'role_id';

    protected $fillable = [
        'name',
        'key',
        'is_active',
        'is_system'
    ];

    // public function users(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         User::class, // 1. Modelo con el que se relaciona
    //         'role_user', // 2. Nombre de la tabla intermedia
    //         'role_id',   // 3. Llave foránea de Role en la tabla intermedia
    //         'user_id'    // 4. Llave foránea de User en la tabla intermedia
    //     );
    // }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'role_user',
            'role_id',   // Llave foránea de Role en la tabla pivot
            'user_id',   // Llave foránea de User en la tabla pivot
            'role_id',   // Llave primaria local en la tabla de roles ("role_id")
            'id'         // Llave primaria local en la tabla de usuarios ("id")
        )
            ->using(RoleUser::class)
            ->withPivot('role_user_id'); // 👈 Obliga a Laravel a cargar la columna UUID de la pivot
    }


}
