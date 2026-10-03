<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenusOptionsRole extends Model  //TABLA PIVOTE
{
    protected $table = 'menus_options_roles';
    protected $primaryKey = 'menu_option_role_id';

    protected $casts = [
        'menu_option_id' => 'int',
        'role_id'        => 'int',
    ];

    protected $fillable = [
        'menu_option_id',
        'role_id',
        // 'created_by',
        // 'updated_by',
    ];

    public function menus_option(): BelongsTo
    {
        return $this->belongsTo(MenusOption::class, 'menu_option_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}