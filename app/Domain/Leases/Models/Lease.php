<?php

namespace Smartville\Domain\Leases\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Users\Models\UserInvitation;

class Lease extends Model
{
    use SoftDeletes,
        UsesFormattedDates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'moved_in_at',
        'moved_out_at',
        'vacated_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status',
        'statusMessage',
        'hasVacated',
        'finishedSetup',
        'formattedMoveIn',
        'formattedMoveOut',
        'formattedVacatedAt',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'moved_in_at',
        'moved_out_at',
        'vacated_at',
        'deleted_at',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lease) {
            $lease->identifier = uniqid(true);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'identifier';
    }

    /**
     * Mark lease property as vacant.
     */
    public function vacateProperty()
    {
        $this->property->updatePropertyToVacantStatus();
    }

    /**
     * Get lease status.
     *
     * @return string
     */
    public function getStatusAttribute()
    {
        if ($this->user_id) {

            if ($this->vacated_at) {
                return 'Vacated';
            }

            if (!$this->moved_out_at || Carbon::now()->lt($this->moved_out_at)) {
                return 'Active';
            }

            return 'Active';
        }

        return 'Awaiting user signup';
    }

    /**
     * Get lease status message.
     *
     * @return null|string
     */
    public function getStatusMessageAttribute()
    {
        if (!$this->vacated_at) {
            return null;
        }

        $human_date = $this->vacated_at->toDayDateTimeString();
        $diffInDays = optional($this->moved_out_at)->diffInDays($this->vacated_at);

        if ($this->moved_out_at && $this->moved_out_at->gt($this->vacated_at)) {
            return "Vacated on {$human_date}. {$diffInDays} days earlier than expected";
        }

        return $human_date;
    }

    /**
     * Get lease vacancy status.
     *
     * @return bool
     */
    public function getHasVacatedAttribute()
    {
        if ($this->vacated_at) {
            return true;
        }

        return false;
    }

    /**
     * Get lease setup status.
     *
     * @return bool
     */
    public function getFinishedSetupAttribute()
    {
        if ($this->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Get formatted move in date.
     *
     * @return mixed
     */
    public function getFormattedMoveInAttribute()
    {
        return $this->moved_in_at != null ? $this->moved_in_at->format('Y-m-d') : null;
    }

    /**
     * Get formatted move out date.
     *
     * @return mixed
     */
    public function getFormattedMoveOutAttribute()
    {
        return $this->moved_out_at != null ? $this->moved_out_at->format('Y-m-d') : null;
    }

    /**
     * Get formatted vacated date.
     *
     * @return mixed
     */
    public function getFormattedVacatedAtAttribute()
    {
        return optional($this->vacated_at)->format('Y-m-d');
    }

    /**
     * Scope query to include only vacated (tenants) leases.
     *
     * @param Builder $builder
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function scopeVacated(Builder $builder)
    {
        return $builder->whereNotNull('moved_out_at')->whereNotNull('vacated_at');
    }

    /**
     * Scope query to include only active (tenants) leases.
     *
     * @param Builder $builder
     * @return Builder|\Illuminate\Database\Query\Builder
     */
    public function scopeActive(Builder $builder)
    {
        return $builder->where('moved_out_at', '>', now())
            ->orWhereNull('moved_out_at')
            ->whereNull('vacated_at');
    }

    /**
     * Get all rent invoices owned by lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentPayments()
    {
        return $this->hasMany(LeasePayment::class);
    }

    /**
     * Get all rent invoices owned by lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentInvoices()
    {
        return $this->hasMany(LeaseInvoice::class);
    }

    /**
     * Get user that owns lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get invitation that ows lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invitation()
    {
        return $this->belongsTo(UserInvitation::class, 'invitation_id');
    }

    /**
     * Get property that owns lease.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
