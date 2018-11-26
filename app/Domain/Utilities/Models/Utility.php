<?php

namespace Smartville\Domain\Utilities\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Money\Money;
use Smartville\App\Tenant\Traits\ForTenants;
use Smartville\App\Traits\Eloquent\HasPrice;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Properties\Models\Property;

class Utility extends Model
{
    use ForTenants,
        Sluggable,
        SoftDeletes,
        HasPrice;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'currency',
        'price',
        'details',
        'billing_interval',
        'billing_duration',
        'billing_day',
        'billing_due',
        'billing_type',
        'billing_unit',
        'usable',
        'all_properties',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formattedPrice',
        'formattedBillingType',
        'formattedBillingInterval',
        'formattedBillingUnit',
    ];

    /**
     * Billing interval map.
     *
     * @var $billingIntervals
     */
    public static $billingIntervals = [
        'monthly' => 'Monthly',
    ];

    /**
     * Formatted billing interval map.
     *
     * @var $billingIntervals
     */
    public static $formattedBillingIntervals = [
        'monthly' => 'month',
    ];

    /**
     * Maximum billing interval map.
     *
     * @var $billingIntervals
     */
    public static $allowedBillingCycles = [
        'monthly' => 11,
    ];

    /**
     * Billing type map.
     *
     * @var $billingTypes
     */
    public static $billingTypes = [
        'varied' => 'Varied',
        'fixed' => 'Fixed',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['identifier', 'name'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    /**
     * Get utility identifier.
     *
     * @return string
     */
    public function getIdentifierAttribute()
    {
        return uniqid(true);
    }

    /**
     * Get property price as instance of Money.
     *
     * @param $value
     * @return Money
     */
    public function getPriceAttribute($value)
    {
        $money = new Money($value, $this->company->currency);

        return $money;
    }

    /**
     * Get formatted utility billing type.
     *
     * @return string
     */
    public function getFormattedBillingTypeAttribute()
    {
        $type = array_get(static::$billingTypes, $this->billing_type);

        return $type;
    }

    /**
     * Get formatted utility billing type.
     *
     * @return string
     */
    public function getFormattedBillingIntervalAttribute()
    {
        $interval = array_get(static::$formattedBillingIntervals, $this->billing_interval);

        if ($this->billing_duration == 1) {
            return 'Every ' . str_plural($interval, $this->billing_duration);
        }

        return 'Every ' . $this->billing_duration . ' ' . str_plural($interval, $this->billing_duration);
    }

    /**
     * Get formatted utility billing unit.
     *
     * @return string
     */
    public function getFormattedBillingUnitAttribute()
    {
        return $this->billing_unit == null ? '-' : $this->billing_unit;
    }

    /**
     * Scope a query to only include for all_properties utilities.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeForAllProperties(Builder $builder)
    {
        return $builder->where('all_properties', true);
    }

    /**
     * Scope a query to only include live (active) utilities.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('usable', true);
    }

    /**
     * Get invoices for the utility.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(UtilityInvoice::class);
    }

    /**
     * Get all of the properties that are assigned this utility.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function properties()
    {
        return $this->morphedByMany(Property::class, 'utilisable');
    }

    /**
     * Get all of the owning utilisable models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function utilisable()
    {
        return $this->morphTo();
    }

    /**
     * Get company that owns amenity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
