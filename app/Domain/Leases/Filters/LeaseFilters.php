<?php

namespace Smartville\Domain\Leases\Filters;

use Smartville\App\Filters\FiltersAbstract;
use Smartville\App\Filters\Tenant\TenantFilter;

class LeaseFilters extends FiltersAbstract
{
    /**
     * A list of filters.
     *
     * @var array
     */
    protected $filters = [
        'tenant' => TenantFilter::class,
        // 'create' => DummyFilter::class,
    ];

    /**
     * A list of default filters.
     *
     * @var array
     */
    protected $defaultFilters = [];

    /**
     * A list of filters map.
     *
     * @return array
     */
    public static function mappings()
    {
        // A sample filters map
        // Each filter consists of a map and a (default) heading
        // Replace map below with one of your choice
        $map = [
            'created' => [
                'map' => [
                    'desc' => 'Latest',
                    'asc' => 'Older'
                ],
                'heading' => 'Date'
            ],
        ];

        return $map;
    }
}
