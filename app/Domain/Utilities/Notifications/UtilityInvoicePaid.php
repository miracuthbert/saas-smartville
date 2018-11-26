<?php

namespace Smartville\Domain\Utilities\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Utilities\Models\UtilityPayment;

class UtilityInvoicePaid extends Notification
{
    use Queueable;

    /**
     * Payment received.
     *
     * @var UtilityPayment
     */
    public $utilityPayment;

    /**
     * Utility invoice.
     *
     * @var invoice
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
     * @param UtilityPayment $utilityPayment
     */
    public function __construct(UtilityPayment $utilityPayment)
    {
        $this->utilityPayment = $utilityPayment;
        $this->invoice = $utilityPayment->invoice;
        $this->utility = $utilityPayment->utility;
        $this->property = $utilityPayment->property;
        $this->user = $utilityPayment->invoice->user;
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
            ->subject("{$this->utility->name} Payment Received")
            ->markdown('tenant.emails.utilities.invoice.paid', [
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
            'body' => "{$this->invoice->formattedInvoiceMonth} payment received. Invoice has been cleared.",
            'title' => "{$this->property->name}, {$this->utility->name} Payment Received",
        ];
    }
}
