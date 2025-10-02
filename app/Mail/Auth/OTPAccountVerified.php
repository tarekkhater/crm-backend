<?php

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class OTPAccountVerified extends Mailable
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
        $settings = Setting::where('id',56)->first()->value;
        // if($settings == '0'){
        //     return true;
        // }
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject('Account verification')
                    ->view('mails.verifiedAccount')
                    ->with('data',['code'=>$this->mailData,'user'=>$this->user]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account verification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.verifiedAccount',
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