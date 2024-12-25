<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;

    public function games()
    {
        return $this->belongsToMany(Game::class)->withTimestamps();
    }

    public function players()
    {
        return $this->belongsToMany(Player::class)->withTimestamps();
    }
    
}
