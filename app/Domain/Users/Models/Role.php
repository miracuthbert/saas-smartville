<?php

namespace Smartville\Domain\Users\Models;

use Smartville\App\Traits\Eloquent\Ordering\OrderableTrait;
use Smartville\App\Traits\Eloquent\Ordering\PivotOrderableTrait;
use Smartville\Domain\Users\Filters\Roles\RoleFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Role extends Model
{
    use NodeTrait, OrderableTrait, PivotOrderableTrait;

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
        if ($this->permissions->isEmpty()) {
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
        return $this->belongsToMany(User::class, 'user_roles')
            ->using(UserRole::class)
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
        return $this->belongsToMany(Permission::class, 'role_permissions')
            ->withTimestamps();
    }
}
