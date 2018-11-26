<?php

namespace Smartville\Domain\Properties\Filters;

use Smartville\App\Filters\FiltersAbstract;
use Smartville\App\Filters\Ordering\CreatedOrder;
use Smartville\Domain\Amenities\Models\Amenity;
use Smartville\Domain\Utilities\Models\Utility;

class PropertyFilters extends FiltersAbstract
{
    /**
     * A list of filters.
     *
     * @var array
     */
    protected $filters = [
         'occupancy_status' => OccupancyStatusFilter::class,
         'status' => StatusFilter::class,
         'amenities' => AmenitiesFilter::class,
         'utilities' => UtilitiesFilter::class,
         'price_order' => PriceOrder::class,
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
        $map = [
            'occupancy_status' => [
                'map' => [
                    'occupied' => 'Occupied',
                    'vacant' => 'Vacant',
                ],
                'heading' => 'Status'
            ],
            'status' => [
                'map' => [
                    'live' => 'Live',
                    'disabled' => 'Disabled',
                ],
                'heading' => 'Status'
            ],
            'amenities' => [
                'map' => Amenity::where('usable', true)->get()->pluck('name', 'slug'),
                'heading' => 'Amenities'
            ],
            'utilities' => [
                'map' => Utility::where('usable', true)->get()->pluck('name', 'slug'),
                'heading' => 'Utilities'
            ],
            'price_order' => [
                'map' => [
                    'desc' => 'Max - Min',
                    'asc' => 'Min - Max'
                ],
                'heading' => 'Order by Price'
            ],
            'created' => [
                'map' => [
                    'desc' => 'Latest',
                    'asc' => 'Older'
                ],
                'heading' => 'Date Added'
            ],
        ];

        return $map;
    }
}
