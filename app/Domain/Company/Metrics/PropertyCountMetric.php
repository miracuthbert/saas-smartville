<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/1/2018
 * Time: 1:55 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Metrics\MetricAbstract;

class PropertyCountMetric extends MetricAbstract
{
    /**
     * Calculate metric.
     *
     * @param Builder $builder
     * @return mixed
     */
    public function calculate(Builder $builder)
    {
        return $builder->count();
    }
}