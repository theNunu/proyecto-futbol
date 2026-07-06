<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPago extends Model
{
    use HasFactory;

    protected $table = 'tipo_pagos';
    protected $primaryKey = 'tipo_pago_id';

    protected $fillable = [
        'nombre',
        'porcentaje_comision',
    ];

    public function transacciones(): HasMany
    {
        return $this->hasMany(Transaccion::class, 'tipo_pago_id');
    }
}