<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/22/2018
 * Time: 10:28 AM
 */

namespace Smartville\App\Tenant\Cache;

use Illuminate\Cache\CacheManager;
use Smartville\App\Tenant\Manager;

class TenantCacheManager extends CacheManager
{
    /**
     * Dynamically call the default driver instance.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($method === 'tags') {
            return $this->store()->tags(
                array_merge($this->getTenantCacheTag(), ...$parameters)
            );
        }

        return $this->store()->tags($this->getTenantCacheTag())->$method(...$parameters);
    }

    /**
     * Get tenant's cache tag.
     *
     * @return array
     */
    protected function getTenantCacheTag()
    {
        return ['tenant_' . optional($this->app[Manager::class]->getTenant())->uuid];
    }
}