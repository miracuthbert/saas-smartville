<?php

namespace Smartville\Domain\Users\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Subscription;
use Laravel\Passport\HasApiTokens;
use Smartville\App\Traits\Eloquent\Auth\HasCompanyInvitation;
use Smartville\App\Traits\Eloquent\Auth\HasConfirmationToken;
use Smartville\App\Traits\Eloquent\Auth\HasTenantInvitation;
use Smartville\App\Traits\Eloquent\Auth\HasTwoFactorAuthentication;
use Smartville\App\Traits\Eloquent\Auth\SendsInvitationTokens;
use Smartville\App\Traits\Eloquent\Dates\UsesFormattedDates;
use Smartville\App\Traits\Eloquent\Roles\HasCompanyPermissions;
use Smartville\App\Traits\Eloquent\Roles\HasPermissions;
use Smartville\App\Traits\Eloquent\Roles\HasRoles;
use Smartville\App\Traits\Eloquent\Subscriptions\HasSubscriptions;
use Smartville\Domain\Comments\Models\Comment;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Issues\Models\Issue;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Smartville\Domain\Leases\Models\LeasePayment;
use Smartville\Domain\Subscriptions\Models\Plan;
use Smartville\Domain\Teams\Models\Team;
use Smartville\Domain\Users\Filters\UserFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Smartville\Domain\Utilities\Models\UtilityInvoice;
use Smartville\Domain\Utilities\Models\UtilityPayment;

class User extends Authenticatable
{
    use Notifiable,
        HasConfirmationToken,
        HasRoles,
        HasPermissions,
        Billable,
        HasSubscriptions,
        SoftDeletes,
        HasTwoFactorAuthentication,
        HasTenantInvitation,
        HasCompanyInvitation,
        HasCompanyPermissions,
        SendsInvitationTokens,
        UsesFormattedDates;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'phone',
        'password',
        'activated',
        'timezone',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'last_login_at',
    ];

    /**
     * The attributes that should be appended to the model.
     *
     * @var array
     */
    protected $appends = [
        'name',
        'lastAccessedCompany'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Scope the query by the request filters.
     *
     * @param Builder $builder
     * @param $request
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new UserFilters($request))->add($filters)->filter($builder);
    }

    /**
     * Get user's full name as attribute.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * Check if user is activated.
     *
     * @return mixed
     */
    public function hasActivated()
    {
        return $this->activated;
    }

    /**
     * Check if user is not activated.
     *
     * @return bool
     */
    public function hasNotActivated()
    {
        return !$this->hasActivated();
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user's team limit reached.
     *
     * @return bool
     */
    public function teamLimitReached()
    {
        return $this->team->users->count() === $this->plan->teams_limit;
    }

    /**
     * Check if current user matches passed user.
     *
     * @param User $user
     * @return bool
     */
    public function isTheSameAs(User $user)
    {
        return $this->id === $user->id;
    }

    /**
     * Get all of the user's comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get issues opened by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    /**
     * Get utility payments made by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function utilityPayments()
    {
        return $this->hasManyThrough(
            UtilityPayment::class,
            UtilityInvoice::class,
            'user_id',
            'invoice_id',
            'id',
            'hash_id'
        );
    }

    /**
     * Get utility invoices charged to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function utilityInvoices()
    {
        return $this->hasMany(UtilityInvoice::class);
    }

    /**
     * Get rent payments made by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function rentPayments()
    {
        return $this->hasManyThrough(
            LeasePayment::class,
            LeaseInvoice::class,
            'user_id',
            'invoice_id',
            'id',
            'hash_id'
        );
    }

    /**
     * Get rent invoices charged to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rentInvoices()
    {
        return $this->hasMany(LeaseInvoice::class);
    }

    /**
     * Get leases owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leases()
    {
        return $this->hasMany(Lease::class);
    }

    /**
     * Get team owned by user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function team()
    {
        return $this->hasOne(Team::class);
    }

    /**
     * Get plan that the user is currently on.
     *
     * @return mixed
     */
    public function plan()
    {
        return $this->plans->first();
    }

    /**
     * Get user's plan as a property.
     *
     * @return mixed
     */
    public function getPlanAttribute()
    {
        return $this->plan();
    }

    /**
     * Get plans owned by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function plans()
    {
        return $this->hasManyThrough(
            Plan::class,
            Subscription::class,
            'user_id',
            'gateway_id',
            'id',
            'stripe_plan'
        )->orderBy('subscriptions.created_at', 'desc');
    }

    /**
     * Get teams that user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_users');
    }

    /**
     * Get user's last accessed company.
     *
     * If using the new tenant switch functionality:
     * Append 'lastAccessedCompany' to model 'appends' property
     * And uncomment lines below
     *
     * @return mixed
     */
    public function getLastAccessedCompanyAttribute()
    {
        return $this->companies()->orderByDesc('last_login_at')->first();
    }

    /**
     * Get companies that user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_users')
            ->withTimestamps();
    }
}
