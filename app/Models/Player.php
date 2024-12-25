<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable =
    [
        'name',
        'user_id'
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // User who owns the player
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Users the player is shared with
    public function sharedWith()
    {
        return $this->belongsToMany(User::class, 'shared_players', 'player_id', 'shared_with_user_id');
    }

}
