<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rubro extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $table = 'rubros';
    protected $primaryKey = 'rubro_id';
    protected $fillable = [
        'nombre',
        'tipo',
        'porcentaje_impuesto',
        'porcentaje_retencion',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class, 'rubro_id');
    }
}