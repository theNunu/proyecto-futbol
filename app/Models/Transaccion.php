<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Transaccion extends Model
{
    use HasFactory;

    protected $table = 'transaccions';

    protected $primaryKey = 'transaccion_id';

    protected $fillable = [
        'rubro_id',
        'tipo_pago_id',
        'descripcion',
        'tipo',
        'monto_bruto',
        'monto_impuesto',
        'monto_retencion',
        'monto_comision',
        'monto_neto',
        'fecha_transaccion',
    ];

    // casts para asegurar que Laravel maneje estos valores siempre como flotantes/strings numéricos exactos en PHP
    protected $casts = [
        'monto_bruto' => 'float',
        'monto_impuesto' => 'float',
        'monto_retencion' => 'float',
        'monto_comision' => 'float',
        'monto_neto' => 'float',
        'fecha_transaccion' => 'datetime',
    ];

    public function rubro(): BelongsTo
    {
        return $this->belongsTo(Rubro::class);
    }

    public function tipoPago(): BelongsTo
    {
        return $this->belongsTo(TipoPago::class);
    }

    public function historialSaldo(): HasOne
    {
        return $this->hasOne(HistorialSaldo::class);
    }
}