<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 4/27/2018
 * Time: 6:25 PM
 */

namespace Smartville\App\Tenant\Traits;

use Smartville\App\Tenant\Models\Tenant;
use Smartville\Domain\Tenant\Models\TenantConnection;
use Webpatser\Uuid\Uuid;

trait IsTenant
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            $tenant->uuid = Uuid::generate(4);
        });

        static::created(function ($tenant) {
            $tenant->tenantConnection()->save(static::newDatabaseConnection($tenant));
        });
    }

    /**
     * Create new tenant database based on tenant id.
     *
     * @param Tenant $tenant
     * @return TenantConnection
     */
    protected static function newDatabaseConnection(Tenant $tenant)
    {
        return new TenantConnection([
            'database' => 'smartville_company_' . $tenant->id
        ]);
    }

    /**
     * Get tenant connection owned by company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function tenantConnection()
    {
        return $this->hasOne(TenantConnection::class, 'company_id', 'id');
    }
}