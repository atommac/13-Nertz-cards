<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendInvitation extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'recipient_email', 'token', 'accepted'];

    // Relationship to the user who sent the invitation
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}