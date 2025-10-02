<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCredit extends Notification
{
    use Queueable;

    public $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','broadcast'];
//        return ['mail','broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(setting('site_name')." Transaction Alert [Credit : $".$this->transaction->amount ."]")
            ->greeting("Dear ".strtoupper($notifiable->name) )
            ->line('<strong>'.setting('site_name'). " Electronic Notification Service </strong>")
            ->line("We wish to inform you that a Credit transaction occurred on your account with Us.")
            ->line(  'The details of this transaction are shown below' )
            ->line( 'Date : ' . $this->transaction->created_at)
            ->line( 'Amount : $'.$this->transaction->amount)
            ->line( 'Description : ' . $this->transaction->reason)
            ->line( 'Deposited By : ' . $this->transaction->initializer_name)
            ->line( "The balances on this account as at ".$this->transaction->created_at." are as follows;")
            ->line( 'Current Balance : ' . $this->transaction->account->balance())
            ->line( 'Available Balance : ' . $this->transaction->account->aBalance());
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
//            "message"      =>  " Your account opening process has been completed, Account number = ".$this->account->account_number,
//            "title"      =>  " Account Opening Process Completed",
//            "invitationTime"   =>  Carbon::now()
        ];
    }
}
