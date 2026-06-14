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
        Schema::create('historial_saldos', function (Blueprint $table) {
            $table->id('historial_saldo_id');
            // $table->foreignId('transaccion_id')->constrained('transacciones')->onDelete('cascade');
            $table->foreignId('transaccion_id')->references('transaccion_id')->on('transaccions')->onDelete('cascade');
            $table->decimal('saldo_anterior', 12, 2);
            $table->decimal('monto_movimiento', 12, 2); // El neto que alteró el saldo
            $table->decimal('saldo_posterior', 12, 2); // Saldo final en ese instante
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_saldos');
    }
};
