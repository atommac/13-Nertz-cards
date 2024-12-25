<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shared_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user sharing the player
            $table->foreignId('player_id')->constrained()->onDelete('cascade'); // The player being shared
            $table->foreignId('shared_with_user_id')->constrained('users')->onDelete('cascade'); // The user the player is shared with
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shared_players');
    }
};
