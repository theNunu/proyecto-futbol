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
        Schema::create('tournament_teams', function (Blueprint $table) {
            $table->id('tournament_team_id');

            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('team_id');

            $table->foreign('tournament_id')
                ->references('tournament_id')
                ->on('tournaments')
                ->onDelete('cascade');

            $table->foreign('team_id')
                ->references('team_id')
                ->on('teams')
                ->onDelete('cascade');
            // $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_teams');
    }
};
