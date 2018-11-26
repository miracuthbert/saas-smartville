<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/1/2018
 * Time: 5:12 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Metrics\MetricAbstract;

class CurrentMonthPropertiesOccupancyRateMetric extends MetricAbstract
{
    /**
     * Calculate metric.
     *
     * @param Builder $builder
     * @return \Illuminate\Support\Collection
     */
    public function calculate(Builder $builder)
    {
        $metrics = collect();
        $property_count = (new PropertyCountMetric())->calculate($builder);
        $properties_occupied_count = $builder->whereHas('currentLease')->whereNotNull('occupied_at')->count();

        $metrics = $metrics->union([
            'property_count' => $property_count,
            'properties_occupied_count' => $properties_occupied_count,
            'properties_occupancy_rate' => $property_count != 0 ? round((($properties_occupied_count / $property_count) * 100), 2) : 0,
        ]);

        return $metrics;
    }
}