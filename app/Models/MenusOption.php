<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MenusOption extends Model
{
    protected $table = 'menus_options';
    protected $primaryKey = 'menu_option_id';

    protected $casts = [
        // 'menu_id'   => 'int',
        'is_active' => 'bool',
    ];

    protected $fillable = [
        // 'menu_id',
        'name',
        'key',
        // 'path',
        'is_active',
        // 'created_by',
        // 'updated_by',
    ];

    /**
     * Relación Muchos a Muchos con Roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'menus_options_roles', 'menu_option_id', 'role_id')
            ->withPivot('menu_option_role_id')
            ->withTimestamps();
    }

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'updated_by');
    // }
}