<?php

namespace Smartville\Domain\Company\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Smartville\App\Tenant\Traits\ForTenants;
use Smartville\App\Traits\Eloquent\Ordering\PivotOrderableTrait;
use Smartville\Domain\Users\Filters\Roles\RoleFilters;
use Smartville\Domain\Users\Models\Permission;
use Smartville\Domain\Users\Models\User;

class CompanyRole extends Model
{
    use ForTenants,
        Sluggable,
        PivotOrderableTrait;

    public static $defaultRoles = [
        'administrator',
        'landlord',
        'caretaker',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'details',
        'usable'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['expires_at'];

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
                'source' => [request()->tenant()->name, 'name'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    public function isTheSameAs(CompanyRole $companyRole)
    {
        return $this->id === $companyRole->id;
    }

    /**
     * Handle adding and deleting of role permissions.
     *
     * @param $permissions
     */
    public function syncPermissions($permissions)
    {
        $this->deleteRemovedPermissions($permissions);

        $this->addPermissions($permissions);
    }

    /**
     * Add permissions to role.
     *
     * @param $ids
     */
    public function addPermissions($ids)
    {
        $this->permissions()->syncWithoutDetaching($ids);
    }

    /**
     * Delete removed permissions from role based on passed ones.
     *
     * @param $ids
     */
    public function deleteRemovedPermissions($ids)
    {
        if ($this->permissions->isEmpty() || !isset($ids)) {
            return;
        }

        $oldPermissions = $this->permissions()->whereNotIn('id', $ids)
            ->pluck('id')
            ->toArray();

        $this->permissions()->detach($oldPermissions);
    }

    /**
     * Filters the result.
     *
     * @param Builder $builder
     * @param $request
     * @param array $filters
     *
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new RoleFilters($request))->add($filters)->filter($builder);
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'company_user_roles', 'role_id')
            ->withTimestamps()
            ->withPivot(['expires_at']);
    }

    /**
     * The permissions that belong to the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'company_role_permissions', 'role_id')
            ->withTimestamps();
    }

    /**
     * Get company that owns property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
