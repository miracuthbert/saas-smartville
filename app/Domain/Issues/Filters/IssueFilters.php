<?php

namespace Smartville\Domain\Issues\Filters;

use Smartville\App\Filters\FiltersAbstract;
use Smartville\App\Filters\Ordering\CreatedOrder;

class IssueFilters extends FiltersAbstract
{
    /**
     * A list of filters.
     *
     * @var array
     */
    protected $filters = [
         'created' => CreatedOrder::class,
    ];

    /**
     * A list of default filters.
     *
     * @var array
     */
    protected $defaultFilters = [
        'created' => 'desc',
    ];

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
                'heading' => 'Created'
            ],
        ];

        return $map;
    }
}
