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
        Schema::create('macth_games', function (Blueprint $table) {
            $table->id('match_id');

            $table->unsignedBigInteger('tournament_id');
            $table->unsignedBigInteger('phase_id');

            $table->unsignedBigInteger('home_team_id');
            $table->unsignedBigInteger('away_team_id');

            $table->integer('home_score');
            $table->integer('away_score');

            $table->unsignedBigInteger('winner_team_id')->nullable();

            $table->timestamp('match_date')->nullable();

            $table->string('status');
            // $table->string('status')->default('scheduled');
            // scheduled | live | finished

            $table->timestamps();
            //TABLAS CON LA QUE SE RELACIONAN

            $table->foreign('tournament_id')
                ->references('tournament_id')
                ->on('tournaments')
                ->cascadeOnDelete();

            $table->foreign('phase_id')
                ->references('phase_id')
                ->on('phases')
                ->cascadeOnDelete();

            $table->foreign('home_team_id')
                ->references('team_id')
                ->on('teams');

            $table->foreign('away_team_id')
                ->references('team_id')
                ->on('teams');

            $table->foreign('winner_team_id')
                ->references('team_id')
                ->on('teams')
                ->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('macth_games');
    }
};
