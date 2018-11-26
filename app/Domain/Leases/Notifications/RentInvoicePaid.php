<?php

namespace Smartville\Domain\Leases\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Leases\Models\LeasePayment;

class RentInvoicePaid extends Notification
{
    use Queueable;

    /**
     * Payment received.
     *
     * @var LeasePayment
     */
    public $leasePayment;

    /**
     * Invoice payment is made under.
     *
     * @var $invoice.
     */
    public $invoice;

    /**
     * Property being payed for.
     *
     * @var $property
     */
    public $property;

    /**
     * The invoice url.
     *
     * @var $url
     */
    public $url;

    /**
     * Create a new notification instance.
     *
     * @param LeasePayment $leasePayment
     */
    public function __construct(LeasePayment $leasePayment)
    {
        $this->leasePayment = $leasePayment;
        $this->invoice = $leasePayment->invoice;
        $this->property = $leasePayment->property;
        $this->url = route('account.invoices.rent.show', $this->invoice);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
            ->subject("{$this->invoice->formattedInvoiceMonth} Rent Payment Received")
            ->markdown('tenant.emails.rent.invoice.paid', [
                'leasePayment' => $this->leasePayment,
                'invoice' => $this->invoice,
                'property' => $this->property,
                'url' => $this->url,
            ]);
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
            'url' => $this->url,
            'body' => "{$this->invoice->formattedInvoiceMonth} rent payment received.",
            'title' => "{$this->property->name} Rent Payment",
        ];
    }
}
