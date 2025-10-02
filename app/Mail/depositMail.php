<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;
class depositMail extends Mailable
{
    use Queueable, SerializesModels;
    public $money;
    public $total;
    public $user;
    public $join_at;
    /**
     * Create a new message instance.
     */
    public function __construct($user,$total,$money,$amount,$join_at)
    {
        $this->user = $user;
        $this->total = $total;
        $this->money = $money;
        $this->amount = $amount;
        $this->join_at= $join_at;
    }


    public function build()
    {
        $settings = Setting::where('id',55)->first()->value;
        // if($settings == '0'){
        //     return true;
        // }
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->subject($this->subject)
                    ->view('mails.depositMail')
                    ->with('data',['user'=>$this->user,'total'=>$this->total,'amount'=>$this->amount,'money'=>$this->money,'date'=>$this->join_at]);
                   
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
            view: 'mails.depositMail',
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