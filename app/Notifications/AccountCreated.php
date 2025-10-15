<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Nigeriabulksms\NigeriabulksmsChannel;
use NotificationChannels\Nigeriabulksms\NigeriabulksmsMessage;

class AccountCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $code;

    public function __construct()
    {
        // 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable  
     * @return array
     */
    public function via($notifiable)
    {
        return [NigeriabulksmsChannel::class];
    }
    

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toNigeriabulksms($notifiable)
    {
		return (new NigeriabulksmsMessage())
                    ->setContent('Assalam '.$notifiable->user->name.', your account has been created successfully. Click Link a Child button to pay your child School Fee')
                    ->setRecipients($notifiable->gsm)
                    ->setFrom('SMMIQGS');

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
            //
        ];
    }
}
