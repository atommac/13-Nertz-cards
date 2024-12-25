<?php

namespace App\Livewire;

use Auth;
use Livewire\Component;
use App\Models\FriendInvitation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\FriendInvitationMail;

class SendFriendInvitation extends Component
{
    public $recipientEmail;

    public function sendInvitation()
    {
        // Validate the recipient email
        $this->validate([
            'recipientEmail' => 'required|email',
        ]);

        $sender = auth()->user();

        // Check if the email already exists in invitations
        $existingInvitation = FriendInvitation::where('recipient_email', $this->recipientEmail)
            ->where('sender_id', $sender->id)
            ->first();

        if ($existingInvitation) {
            session()->flash('error', 'An invitation to this email has already been sent.');
            return;
        }

        // Create a new invitation with a unique token
        $invitation = FriendInvitation::create([
            'sender_id' => $sender->id,
            'recipient_email' => $this->recipientEmail,
            'token' => Str::uuid(),
        ]);

        // Send the email with the invitation link
        Mail::to($this->recipientEmail)->send(new FriendInvitationMail($sender, $invitation));

        session()->flash('success', 'Invitation sent successfully!');
        $this->recipientEmail = '';
    }

    public function render()
    {
        return view('livewire.send-friend-invitation');
    }
}