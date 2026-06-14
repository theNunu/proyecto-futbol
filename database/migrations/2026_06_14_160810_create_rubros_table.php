<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rubros', function (Blueprint $table) {
            $table->id('rubro_id');
            $table->string('nombre');
            $table->string('tipo', 30); //ingreso , egreso
            $table->decimal('porcentaje_impuesto', 5, 2)->default(0.00); // Ej: 15.00 para 15%
            $table->decimal('porcentaje_retencion', 5, 2)->default(0.00); // Ej: 2.00 para 2%
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubros');
    }
};
