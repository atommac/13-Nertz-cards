<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\FriendInvitation;

class HandleFriendInvitationAfterRegistration
{
    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $token = session('friend_invitation_token');
        
        if ($token) {
            $invitation = FriendInvitation::where('token', $token)
                ->where('recipient_email', $event->user->email)
                ->first();

            if ($invitation) {
                // Create mutual friendship
                $event->user->friends()->attach($invitation->sender_id);
                $invitation->sender->friends()->attach($event->user->id);

                // Share sender's players with the new user
                $senderPlayers = $invitation->sender->players;
                foreach ($senderPlayers as $player) {
                    $event->user->sharedPlayers()->attach($player->id, ['user_id' => $invitation->sender_id]);
                }

                // Mark invitation as accepted
                $invitation->update(['accepted' => true]);

                // Clear the token from session
                session()->forget('friend_invitation_token');

                // Add a success message
                session()->flash('success', 'Your account has been created and friendship confirmed with ' . $invitation->sender->name);
            }
        }
    }
} 