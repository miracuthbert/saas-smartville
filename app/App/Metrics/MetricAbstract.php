<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/30/2018
 * Time: 3:27 PM
 */

namespace Smartville\App\Metrics;

use Illuminate\Database\Eloquent\Builder;

abstract class MetricAbstract
{
    /**
     * The metric to be returned.
     *
     * @var array
     */
    public $metric = [];

    /**
     * Calculate metric.
     *
     * @param Builder $builder
     * @return mixed
     */
    public abstract function calculate(Builder $builder);

    /**
     * Get metric.
     *
     * @return mixed
     */
    public function metric()
    {
        return $this->metric;
    }
}