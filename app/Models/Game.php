<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory;

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function hands()
    {
        return $this->hasMany(Hand::class);
    }
}
