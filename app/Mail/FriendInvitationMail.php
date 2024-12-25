<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FriendInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sender;
    public $token;


    /**
     * Create a new message instance.
     */
    public function __construct($sender, $token)
    {
        $this->sender = $sender;

        // If $token is an object, extract the 'token' property
        $this->token = is_object($token) ? $token->token : $token;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Friend Invitation Mail',
        );
    }

    public function build()
    {
        $url = url('/friend-invite/' . $this->token);
        return $this->subject('You Have a New Friend Invitation')
                    ->view('emails.friend-invitation')
                    ->with([
                        'senderName' => $this->sender->name,
                        'invitationLink' => $url,
                    ]);
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [];
    // }
}
