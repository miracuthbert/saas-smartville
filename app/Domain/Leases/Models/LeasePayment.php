<?php

namespace Smartville\Domain\Leases\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\HashIds\Traits\UsesHashIds;
use Smartville\App\Money\Money;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\App\Traits\Eloquent\HasAmount;
use Smartville\Domain\Company\Models\CompanyPaymentMethod;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;

class LeasePayment extends Model
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
        'payment_method_id',
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
//        'formattedAmount',
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
     * Get and return price as instance of Money.
     *
     * @param $value
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
     * Get the transaction's payment method.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function paymentMethod()
    {
        return $this->hasOne(CompanyPaymentMethod::class, 'id', 'payment_method_id');
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
        return $this->belongsTo(LeaseInvoice::class, 'invoice_id', 'hash_id');
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
}
