<?php

namespace Smartville\Domain\Leases\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Leases\Models\LeaseInvoice;

class NewRentInvoice extends Notification
{
    use Queueable;

    /**
     * Invoice being sent.
     *
     * @var LeaseInvoice
     */
    public $leaseInvoice;

    /**
     * Property being charged for.
     *
     * @var $property
     */
    public $property;

    /**
     * User (tenant) being charged.
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
     * @param LeaseInvoice $leaseInvoice
     */
    public function __construct(LeaseInvoice $leaseInvoice)
    {
        $this->leaseInvoice = $leaseInvoice;
        $this->property = $leaseInvoice->property;
        $this->user = $leaseInvoice->user;
        $this->url = route('account.invoices.rent.show', $leaseInvoice);
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
            ->subject("{$this->leaseInvoice->formattedInvoiceMonth} Rent Invoice")
            ->markdown('tenant.emails.rent.invoice.new', [
                'leaseInvoice' => $this->leaseInvoice,
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
            'body' => "The rent invoice for {$this->leaseInvoice->formattedInvoiceMonth} is ready.",
            'title' => "{$this->property->name} Rent Invoice",
        ];
    }
}
