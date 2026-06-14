<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaccions', function (Blueprint $table) {
            $table->id('transaccion_id');
            $table->foreignId('rubro_id')->references('rubro_id')->on('rubros')->onDelete('restrict');
            $table->foreignId('tipo_pago_id')->references('tipo_pago_id')->on('tipo_pagos')->onDelete('restrict');
            $table->string('descripcion');
            $table->string('tipo', 30); //'ingreso', 'egreso', 'abono']
            
            // Valores financieros de precisión
            $table->decimal('monto_bruto', 12, 2);
            $table->decimal('monto_impuesto', 12, 2)->default(0.00);
            $table->decimal('monto_retencion', 12, 2)->default(0.00);
            $table->decimal('monto_comision', 12, 2)->default(0.00);
            $table->decimal('monto_neto', 12, 2);
            
            $table->dateTime('fecha_transaccion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaccions');
    }
};
