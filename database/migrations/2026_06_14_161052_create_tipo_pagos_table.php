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
        Schema::create('tipo_pagos', function (Blueprint $table) {
            $table->id('tipo_pago_id');
            $table->string('nombre'); // Ej: Tarjeta, Transferencia, Efectivo
            $table->decimal('porcentaje_comision', 5, 2)->default(0.00); // Ej: 3.50%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_pagos');
    }
};
