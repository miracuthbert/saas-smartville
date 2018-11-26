<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/1/2018
 * Time: 4:58 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Metrics\MetricAbstract;

class PropertiesOccupiedCountMetric extends MetricAbstract
{
    /**
     * Calculate metric.
     *
     * @param Builder $builder
     * @return mixed
     */
    public function calculate(Builder $builder)
    {
        return $builder->whereHas('currentLease')
            ->whereNotNull('occupied_at')
            ->count();
    }
}