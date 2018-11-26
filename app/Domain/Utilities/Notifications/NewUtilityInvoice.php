<?php

namespace Smartville\Domain\Utilities\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Utilities\Models\UtilityInvoice;

class NewUtilityInvoice extends Notification
{
    use Queueable;

    /**
     * Utility invoice.
     *
     * @var UtilityInvoice
     */
    public $invoice;

    /**
     * Utility being billed for.
     *
     * @var $utility
     */
    public $utility;

    /**
     * Property being charged for.
     *
     * @var $property
     */
    public $property;

    /**
     * User (or Tenant) being charged.
     *
     * @var $user
     */
    public $user;

    /**
     * The invoice url.
     *
     * @var $url
     */
    public $url;

    /**
     * Create a new notification instance.
     *
     * @param UtilityInvoice $utilityInvoice
     */
    public function __construct(UtilityInvoice $utilityInvoice)
    {
        $this->invoice = $utilityInvoice;
        $this->utility = $utilityInvoice->utility;
        $this->property = $utilityInvoice->property;
        $this->user = $utilityInvoice->user;
        $this->url = route('account.invoices.utilities.show', $this->invoice);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject("{$this->utility->name} New Invoice")
            ->markdown('tenant.emails.utilities.invoice.new', [
                'invoice' => $this->invoice,
                'utility' => $this->utility,
                'property' => $this->property,
                'user' => $this->user,
                'url' => $this->url,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'url' => $this->url,
            'body' => "The invoice for {$this->invoice->formattedInvoiceMonth} is ready.",
            'title' => "{$this->property->name}, {$this->utility->name} Invoice",
        ];
    }
}
