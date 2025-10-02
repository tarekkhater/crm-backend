<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class OTPAccountForgetPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $mailData;
    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($user,$mailData)
    {
        $this->user = $user;
        $this->mailData = $mailData;
    }


    public function build()
    {
        // $htmlContent = view('EmailsNew.OTPFogetPasswoprd')->render(); // render the view content
        // $cssToInline = new CssToInlineStyles();
        // $htmlWithInlineStyles = $cssToInline->convert($htmlContent); 
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Account FogetPassword')
                    ->view('mails.forgetpassword')
                    ->with('data',['code'=>$this->mailData,'user'=>$this->user]);
                   
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account FogetPassword',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.forgetpassword',
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