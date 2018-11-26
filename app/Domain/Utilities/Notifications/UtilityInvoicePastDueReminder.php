<?php

namespace Smartville\Domain\Utilities\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Smartville\Domain\Utilities\Models\UtilityInvoice;

class UtilityInvoicePastDueReminder extends Notification
{
    use Queueable;

    /**
     * Utility invoice.
     *
     * @var $invoice
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
     * @param UtilityInvoice $utilityInvoice
     */
    public function __construct(UtilityInvoice $utilityInvoice)
    {
        $this->invoice = $utilityInvoice;
        $this->utility = $utilityInvoice->utility;
        $this->property = $utilityInvoice->property;
        $this->user = $utilityInvoice->user;
        $this->dueDate = $utilityInvoice->due_at->diffForHumans();
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
            ->subject("{$this->utility->name} Payment Past Due")
            ->markdown('tenant.emails.utilities.invoice.past', [
                'invoice' => $this->invoice,
                'utility' => $this->utility,
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
            'body' => "The payment for {$this->invoice->formattedInvoiceMonth} was expected by {$this->invoice->formattedDueAt} ({$this->dueDate}). 
            The expected balance has not been cleared yet. Please clear your balance or notify us if payment has been made.",
            'title' => "{$this->property->name}, {$this->utility->name} Payment Past Due",
        ];
    }
}
