<?php

namespace Smartville\Domain\Leases\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Leases\Models\LeaseInvoice;

class RentInvoicePastDueReminder extends Notification
{
    use Queueable;

    /**
     * Invoice being sent.
     *
     * @var $invoice
     */
    public $invoice;

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
     * Date payment due.
     *
     * @var $dueDate
     */
    public $dueDate;

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
        $this->invoice = $leaseInvoice;
        $this->property = $leaseInvoice->property;
        $this->user = $leaseInvoice->user;
        $this->dueDate = $leaseInvoice->due_at->diffForHumans();
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
        return ['database', 'mail'];
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
            ->subject("{$this->invoice->formattedInvoiceMonth} Rent Payment Past Due")
            ->markdown('tenant.emails.rent.invoice.past', [
                'invoice' => $this->invoice,
                'property' => $this->property,
                'user' => $this->user,
                'dueDate' => $this->dueDate,
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
            'body' => "The rent for {$this->invoice->formattedInvoiceMonth} was expected by {$this->invoice->formattedDueAt} ({$this->dueDate}). 
            The expected balance has not been cleared yet. Please clear your balance or notify us if payment has been made.",
            'title' => "{$this->property->name} Rent Payment Past Due",
        ];
    }
}
