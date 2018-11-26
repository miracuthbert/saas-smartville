<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/14/2018
 * Time: 7:55 PM
 */

namespace Smartville\App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Smartville\Domain\Leases\Notifications\NewRentInvoice;
use Smartville\Domain\Leases\Notifications\RentInvoiceDueReminder;
use Smartville\Domain\Leases\Notifications\RentInvoicePastDueReminder;

class RentInvoiceNotifier
{
    /**
     * The specific invoice send date and time.
     *
     * @var Carbon|\DateTime|null
     */
    private $time;

    /**
     * RentInvoiceNotifier constructor.
     *
     * @param \DateTime|null|Carbon $time
     */
    public function __construct($time = null)
    {
        $this->time = $time;
    }

    /**
     * Send notifications for utility invoices past due.
     *
     * @param int $days
     * @param Carbon|null $time
     */
    public function sendPastDueReminder($days = 1, Carbon $time = null)
    {
        if ($time == null) {
            $time = $this->time != null ? Carbon::parse($this->time) : Carbon::now();
        }

        // dates
        $now = $days == 1 ? $time : $time->subDays($days);

        // find invoices
        $invoices = LeaseInvoice::with('user', 'property')
            ->whereDate('due_at', '<', $now)
            ->whereNotNull('sent_at')
            ->whereNull('cleared_at')
            ->get();

        // send notifications
        $invoices->each(function ($invoice, $key) use ($time) {

            // min to delay from sent_at date
            $delay = now()->addSeconds(30);

            $invoice->user->notify(
                (new RentInvoicePastDueReminder($invoice))->delay($delay)
            );
        });

        // optional: call event / webhook
        // Log::info("Sent rent past due notifications to {$invoices->count()}");
    }

    /**
     * Send notifications for due utility invoices.
     *
     * @param int $days
     * @param Carbon|null $time
     */
    public function sendDueReminder($days = 3, Carbon $time = null)
    {
        if ($time == null) {
            $time = $this->time != null ? Carbon::parse($this->time) : Carbon::now();
        }

        // dates
        $now = $days == 1 ? $time->subDay() : $time;
        $due = $now->copy()->addDays($days);

        // find invoices
        $invoices = LeaseInvoice::with('user', 'property')
            ->whereBetween('due_at', [$now, $due])
            ->whereNotNull('sent_at')
            ->whereNull('cleared_at')
            ->get();

        // send notifications
        $invoices->each(function ($invoice, $key) use ($time) {

            // min to delay from sent_at date
            $delay = now()->addSeconds(30);

            $invoice->user->notify(
                (new RentInvoiceDueReminder($invoice))->delay($delay)
            );
        });

        // optional: call event / webhook
        // Log::info("Sent rent due notifications to {$invoices->count()}");
    }

    /**
     * Send notifications for new rent invoices.
     */
    public function sendNew()
    {
        $time = $this->time != null ? Carbon::parse($this->time) : Carbon::now();

        // find invoices
        $invoices = LeaseInvoice::with('user', 'property')
            ->whereNotNull('sent_at')
            ->whereDate('sent_at', $time->toDateString())
            ->whereTime('sent_at', $time->toTimeString())
            ->whereNull('cleared_at')
            ->get();

        // send notifications
        $invoices->each(function ($invoice, $key) use ($time) {

            // min to delay from sent_at date
            $delay = now()->addMinutes($time->copy()->diffInMinutes($invoice->sent_at));

            $invoice->user->notify(
                (new NewRentInvoice($invoice))->delay($delay)
            );
        });

        // optional: call event / webhook
        // Log::info("Sent notifications to {$invoices->count()}");
    }
}