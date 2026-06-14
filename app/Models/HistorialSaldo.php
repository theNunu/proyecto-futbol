<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialSaldo extends Model
{
    use HasFactory;

    protected $table = 'historial_saldos';
    protected $primaryKey = 'historial_saldo_id';

    protected $fillable = [
        'transaccion_id',
        'saldo_anterior',
        'monto_movimiento',
        'saldo_posterior',
    ];

    protected $casts = [
        'saldo_anterior' => 'float',
        'monto_movimiento' => 'float',
        'saldo_posterior' => 'float',
    ];

    public function transaccion(): BelongsTo
    {
        return $this->belongsTo(Transaccion::class);
    }
}