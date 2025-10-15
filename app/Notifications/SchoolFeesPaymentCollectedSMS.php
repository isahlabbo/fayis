<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use NotificationChannels\Nigeriabulksms\NigeriabulksmsChannel;
use NotificationChannels\Nigeriabulksms\NigeriabulksmsMessage;

class SchoolFeesPaymentCollectedSMS extends Notification
{
    use Queueable;

    public $invoice = null;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($invoice)
    {
        
        $this->invoice = $invoice;
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
                    ->setContent('This is to notify you that, the '.$this->invoice->sectionClassStudentTerm->academicSessionTerm->term->name.' School Fees payment of #'.$this->invoice->amount.' was successfully collected for '.$this->invoice->sectionClassStudentTerm->sectionClassStudent->student->name.' of '.$this->invoice->sectionClassStudentTerm->sectionClassStudent->sectionClass->name)
                    ->setRecipients(['08036444622','07082884500'])
                    ->setFrom('SMMIQGS');

    }

    
}
