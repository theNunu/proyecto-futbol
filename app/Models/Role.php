<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // public function catalog_details(): HasMany
    // {
    //     return $this->hasMany(CatalogDetail::class, 'catalog_id');
    // }
}
