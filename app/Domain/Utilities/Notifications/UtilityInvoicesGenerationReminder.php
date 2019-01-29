<?php

namespace Smartville\Domain\Utilities\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Utilities\Models\Utility;

class UtilityInvoicesGenerationReminder extends Notification
{
    use Queueable;

    /**
     * @var Utility
     */
    public $utility;

    /**
     * Create a new notification instance.
     *
     * @param Utility $utility
     */
    public function __construct(Utility $utility)
    {
        $this->utility = $utility;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'title' => $this->utility->name . ' invoices generation reminder',
            'body' => 'You have scheduled invoices for ' . $this->utility->name . ' to be generated today. Do not forget to create them.',
            'url' => route('tenant.utilities.invoices.create', $this->utility),
        ];
    }
}
