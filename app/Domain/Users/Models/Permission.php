<?php

namespace Smartville\Domain\Users\Models;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Traits\Eloquent\Ordering\OrderableTrait;
use Illuminate\Database\Eloquent\Model;
use Smartville\App\Traits\Eloquent\UsableTrait;
use Smartville\Domain\Company\Models\CompanyRole;

class Permission extends Model
{
    use OrderableTrait,
        UsableTrait;

    protected $fillable = [
        'name',
        'for_company',
        'usable',
    ];

    /**
     * Scope a query to only include company roles.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeForCompany(Builder $builder)
    {
        return $builder->where('for_company', true);
    }

    /**
     * Scope a query to only include non company roles.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeNotForCompany(Builder $builder)
    {
        return $builder->where('for_company', false);
    }

    /**
     * The company roles that belong to the permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companyRoles()
    {
        return $this->belongsToMany(
            CompanyRole::class,
            'company_role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * The roles that belong to the permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions')
            ->withTimestamps();
    }
}
