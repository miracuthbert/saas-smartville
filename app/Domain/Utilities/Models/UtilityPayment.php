<?php

namespace Smartville\Domain\Utilities\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\HashIds\Traits\UsesHashIds;
use Smartville\App\Money\Money;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\App\Traits\Eloquent\HasAmount;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;

class UtilityPayment extends Model
{
    use SoftDeletes,
        UsesHashIds,
        UsesFormattedDates,
        HasAmount;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'description',
        'paid_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'paid_at',
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
//        'formattedPaidAt',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'invoice',
    ];

    /**
     * Get payment amount.
     *
     * @return Money
     */
    public function getAmountAttribute($value)
    {
        return new Money($value, $this->invoice->currency);
    }

    /**
     * Get formatted pay date.
     *
     * @return string
     */
    public function getFormattedPaidAtAttribute()
    {
        return $this->paid_at != null ? $this->paid_at->format('Y-m-d') : null;
    }

    /**
     * Get company user that processed payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get invoice that payment is made for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(UtilityInvoice::class, 'invoice_id', 'hash_id');
    }

    /**
     * Get property payment is made for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get lease that payment was made under.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lease()
    {
        return $this->belongsTo(Lease::class);
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
