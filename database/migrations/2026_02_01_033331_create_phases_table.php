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
        Schema::create('phases', function (Blueprint $table) {
            $table->id('phase_id');

            $table->unsignedBigInteger('tournament_id');

            $table->string('name');
            // Example: "Round of 16", "Quarterfinals"

            $table->integer('order');
            // Used to know which phase comes first

            $table->boolean('has_extra_time'); 
            $table->boolean('has_penalties');

            $table->timestamps();

            $table->foreign('tournament_id')
                ->references('tournament_id')
                ->on('tournaments')
                ->onDelete('cascade');
            // $table->id();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phases');
    }
};
