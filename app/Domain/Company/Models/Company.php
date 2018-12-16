<?php

namespace Smartville\Domain\Company\Models;

use Illuminate\Database\Eloquent\Model;
use Smartville\App\Tenant\Models\Tenant;
use Smartville\App\Tenant\Traits\IsTenant;
use Smartville\App\Traits\Eloquent\Auth\SendsInvitationTokens;
use Smartville\Domain\Issues\Models\Issue;
use Smartville\Domain\Leases\Models\Lease;
use Smartville\Domain\Leases\Models\LeaseInvoice;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Utilities\Models\UtilityInvoice;

class Company extends Model implements Tenant
{
    use IsTenant,
        SendsInvitationTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country',
        'email',
        'phone',
        'currency',
        'timezone',
        'short_name',
    ];

    /**
     * Get all of the company issues.
     *
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     */
    public function issues()
    {
        return $this->morphToMany(Issue::class, 'issueable', 'issue_topics');
    }

    /**
     * Get all utility invoices for company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function utilityInvoices()
    {
        return $this->hasManyThrough(UtilityInvoice::class, Property::class);
    }

    /**
     * Get all rent invoices for company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function rentInvoices()
    {
        return $this->hasManyThrough(LeaseInvoice::class, Property::class);
    }

    /**
     * Get all leases owned by company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function leases()
    {
        return $this->hasManyThrough(Lease::class, Property::class);
    }

    /**
     * Get properties owned by company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get users that belong to company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userRoles()
    {
        return $this->belongsToMany(User::class, 'company_user_roles', 'user_id')
            ->withTimestamps()
            ->withPivot(['expires_at']);
    }

    /**
     * Get roles owned by company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
        return $this->hasMany(CompanyRole::class);
    }

    /**
     * Get users that belong to company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_users')
            ->withTimestamps();
    }
}
