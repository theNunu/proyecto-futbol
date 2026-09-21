<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids; // 👈 Importa el trait de UUIDs nativo
use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleUser extends Pivot
{
    use HasUuids; // 👈 Usa el trait nativo para que genere el UUID solo

    protected $table = 'role_user';
    protected $primaryKey = 'role_user_id';
    protected $keyType = 'string';
    public $incrementing = false;
}
