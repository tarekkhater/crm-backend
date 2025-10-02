<?php

namespace App\Mail\Files;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FileSendUsers extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($user,$file)
    {
        $this->user = $user;
        $this->file = $file;
    }


    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Files Users')
                    ->view('emails.files')
                    ->attach($this->file, [
                        'as' => "FilesUsers.xls", // Custom filename for the attachment
                        'mime' => 'application/octet-stream', // MIME type
                    ]);
                    // ->with('data',['file'=>$this->file,'user'=>$this->user]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Files Users',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.files',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}