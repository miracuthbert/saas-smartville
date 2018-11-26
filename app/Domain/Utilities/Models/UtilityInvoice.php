<?php

namespace Smartville\Domain\Utilities\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\HashIds\Traits\UsesHashIds;
use Smartville\App\Money\Money;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\App\Traits\Eloquent\HasAmount;
use Smartville\App\Traits\Eloquent\HasPrice;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Utilities\Filters\UtilityInvoiceFilters;

class UtilityInvoice extends Model
{
    use SoftDeletes,
        UsesHashIds,
        UsesFormattedDates,
        HasPrice,
        HasAmount;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency',
        'price',
        'previous',
        'current',
        'start_at',
        'end_at',
        'sent_at',
        'due_at',
        'cleared_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_at',
        'end_at',
        'sent_at',
        'due_at',
        'cleared_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
//        'status',
//        'isPastDue',
//        'isCleared',
//        'isSent',
//        'isDraft',
//        'formattedInvoiceMonth',
//        'formattedSentAt',
//        'formattedDueAt',
//        'formattedClearedAt',
//        'formattedPaymentTotal',
//        'formattedOutstandingBalance',
    ];

    /**
     * Get and return price as instance of Money.
     *
     * @param $value
     * @return Money
     */
    public function getPriceAttribute($value)
    {
        return new Money($value, $this->currency);
    }

    /**
     * Get invoice billing amount.
     *
     * @return Money
     */
    public function getAmountAttribute($value)
    {
        $value = $this->priceAmount;

        if ($this->previous && $this->current) {
            $diff = ($this->current - $this->previous);

            $value = ($value * $diff);
        }

        return new Money($value, $this->currency);
    }

    /**
     * Get difference between billing interval.
     *
     * @return mixed
     */
    public function getDifferenceOfBillingInterval()
    {
        $interval = $this->utility->billing_interval;
        $interval = str_plural(studly_case(array_get(Utility::$formattedBillingIntervals, $interval)));

        return $this->start_at->diffIn{$interval}($this->end_at);
    }

    /**
     * Get invoice status.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if (!$this->sent_at) {
            return "Draft";
        }

        if (!$this->cleared_at && Carbon::now()->lt($this->sent_at)) {
            return "Queued";
        }

        if (!$this->cleared_at && Carbon::now()->lt($this->due_at)) {
            return "Awaiting payment";
        }

        if (!$this->cleared_at && Carbon::now()->isSameDay($this->due_at)) {
            return "Due today";
        }

        if (!$this->cleared_at && Carbon::now()->gt($this->due_at)) {
            return "Past due date";
        }

        return "Cleared";
    }

    /**
     * Return if invoice is past due.
     *
     * @return bool
     */
    public function getIsPastDueAttribute()
    {
        if (!$this->cleared_at && Carbon::now()->gt($this->due_at)) {
            return true;
        }

        return false;
    }

    /**
     * Return if invoice is cleared.
     *
     * @return bool
     */
    public function getIsClearedAttribute()
    {
        if ($this->cleared_at) {
            return true;
        }

        return false;
    }

    /**
     * Return if invoice is sent.
     *
     * @return bool
     */
    public function getIsSentAttribute()
    {
        if ($this->sent_at && Carbon::now()->lt($this->sent_at)) {
            return true;
        }

        return false;
    }

    /**
     * Return if invoice is draft.
     *
     * @return bool
     */
    public function getIsDraftAttribute()
    {
        if (!$this->sent_at || Carbon::now()->gt($this->sent_at)) {
            return true;
        }

        return false;
    }

    /**
     * Get formatted due date.
     *
     * @return string
     */
    public function getFormattedInvoiceMonthAttribute()
    {
        if ($this->end_at->month > $this->start_at->month) {
            return "{$this->start_at->format('M')} - {$this->end_at->format('M, Y')}";
        }

        return $this->start_at != null ? $this->start_at->format('M, Y') : null;
    }

    /**
     * Get formatted invoice send date.
     *
     * @return string
     */
    public function getFormattedSentAtAttribute()
    {
        return $this->sent_at != null ? $this->sent_at->format('Y-m-d') : null;
    }

    /**
     * Get formatted due date.
     *
     * @return string
     */
    public function getFormattedDueAtAttribute()
    {
        return $this->due_at != null ? $this->due_at->format('Y-m-d') : null;
    }

    /**
     * Get formatted pay date.
     *
     * @return string
     */
    public function getFormattedClearedAtAttribute()
    {
        return $this->cleared_at != null ? $this->cleared_at->format('Y-m-d') : null;
    }

    /**
     * Get formatted paid total.
     *
     * @return mixed
     */
    public function getFormattedPaymentTotalAttribute()
    {
        return (new Money($this->paymentsTotal(), $this->currency))->formatted();
    }

    /**
     * Get formatted remaining balance.
     *
     * @return mixed
     */
    public function getFormattedOutstandingBalanceAttribute()
    {
        return (new Money($this->outstandingBalance(), $this->currency))->formatted();
    }

    /**
     * Get remaining balance.
     *
     * @return int
     */
    public function outstandingBalance()
    {
        $value = ($this->initialAmount - $this->paymentsTotal());

        return $value;
    }

    /**
     * Get total sum of payments made.
     *
     * @return int
     */
    public function paymentsTotal()
    {
        $payments = $this->payments->sum(function ($payment) {
            return $payment->initialAmount;
        });

        return $payments;
    }

    /**
     * Scope query by passed filters.
     *
     * @param Builder $builder
     * @param $request
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new UtilityInvoiceFilters($request))->add($filters)->filter($builder);
    }

    /**
     * Get payments made for invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(UtilityPayment::class, 'invoice_id', 'hash_id');
    }

    /**
     * Get user that owns invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get lease that owns invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Get property that owns invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get utility that owns invoice.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function utility()
    {
        return $this->belongsTo(Utility::class);
    }
}
