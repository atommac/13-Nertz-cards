<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up()
    {
        Schema::create('friend_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user who is initiating the friendship
            $table->foreignId('friend_id')->constrained('users')->onDelete('cascade'); // The friend
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('friend_user');
    }
};
