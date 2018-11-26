<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/20/2018
 * Time: 4:44 AM
 */

namespace Smartville\App\Traits\Eloquent\Roles;

use Carbon\Carbon;
use Smartville\Domain\Company\Models\CompanyRole;

trait HasCompanyPermissions
{
    /**
     * Check if given permission exists and has not expired.
     *
     * @param $permission
     * @return bool
     */
    public function hasCompanyPermissionTo($permission)
    {
        $roles = $this->companyRoles()
            ->whereNull('expires_at')
            ->orWhere('expires_at', '>', Carbon::now())
            ->get();

        foreach ($roles as $role) {
            if ($role->permissions->contains('id', $permission->id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the company roles that belong to the model.
     */
    public function companyRoles()
    {
        return $this->belongsToMany(
            CompanyRole::class,
            'company_user_roles',
            'user_id',
            'role_id'
        )->withTimestamps()
            ->withPivot(['expires_at']);
    }
}