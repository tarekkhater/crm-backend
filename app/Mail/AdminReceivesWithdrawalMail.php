<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
class AdminReceivesWithdrawalMail extends Mailable
{
    use Queueable, SerializesModels;
    public $messages;
    public $subject;
    public $user;
    public $total;
    /**
     * Create a new message instance.
     */
    public function __construct($user,$total,$subject,$messages)
    {
        $this->user = $user;
        $this->total = $total;
        $this->subject = $subject;
        $this->messages = $messages;
    }


    public function build()
    {
        $settings = Setting::where('id',24)->first()->value;
        // if($settings == '0'){
        //     return null;
        // }
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject($this->subject)
                    ->view('mails.ReceivesWithdrawalMail')
                    ->with('data',['user'=>$this->user,'title'=>$this->subject,'content'=>$this->messages,'total'=>$this->total]);
                   
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.ReceivesWithdrawalMail',
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