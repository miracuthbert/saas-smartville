<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 4/28/2018
 * Time: 12:04 AM
 */

namespace Smartville\App\Tenant;

use Smartville\App\Tenant\Models\Tenant;

class Manager
{
    protected $tenant;

    /**
     * Get tenant.
     *
     * @return mixed
     */
    public function getTenant()
    {
        return $this->tenant;
    }

    /**
     * Set tenant.
     *
     * @param Tenant $tenant
     */
    public function setTenant(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Check if tenant exists in request.
     *
     * @return bool
     */
    public function hasTenant()
    {
        return isset($this->tenant);
    }
}