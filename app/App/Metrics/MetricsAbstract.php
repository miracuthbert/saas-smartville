<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/30/2018
 * Time: 3:08 PM
 */

namespace Smartville\App\Metrics;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class MetricsAbstract
{
    /**
     * A list of metrics.
     *
     * @var array
     */
    protected $metrics = [];

    /**
     * A list of default metrics.
     *
     * @var array
     */
    protected $defaultMetrics = [];

    /**
     * The request.
     *
     * @var Request
     */
    protected $request;

    /**
     * MetricsAbstract constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Loops through metrics and builds them up.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function filter(Builder $builder)
    {
        foreach ($this->getMetrics() as $filter => $value) {
            $this->resolveFilter($filter)->filter($builder, $value);
        }

        return $builder;
    }

    /**
     * Add metrics.
     *
     * @param array $metrics
     * @return $this
     */
    public function add(array $metrics)
    {
        $this->metrics = array_merge($this->metrics, $metrics);

        return $this;
    }

    /**
     * Returns all metrics.
     *
     * @return array
     */
    public function getMetrics()
    {
        //return default metrics if request is empty
        if ($this->filteredMetricsCount() == 0) {
            return $this->getDefaultMetrics();
        }

        $this->removeDefaultMetricsIfPresentInRequest();

        if ($this->filteredMetricsCount() != 0 && $this->defaultMetricsCount() != 0) {
            return $this->mergeDefaultMetricsWithRequestMetrics();
        }

        //return only metrics in request
        return $this->filterMetrics($this->metrics);
    }

    /**
     * Returns default metrics.
     *
     * @return array
     */
    protected function getDefaultMetrics()
    {
        return $this->defaultMetrics;
    }

    /**
     * Return count of default metrics.
     *
     * @return int
     */
    public function defaultMetricsCount()
    {
        return count($this->defaultMetrics);
    }

    /**
     * Return count of filtered metrics.
     *
     * @return int
     */
    public function filteredMetricsCount()
    {
        return count($this->filterMetrics($this->metrics));
    }

    /**
     * Merges default metrics not present in request (with passed metrics).
     */
    protected function mergeDefaultMetricsWithRequestMetrics()
    {
        $cleanMetrics = $this->filterMetrics($this->metrics);

        $mergedMetrics = array_merge($cleanMetrics, $this->defaultMetrics);

        return $mergedMetrics;
    }

    /**
     * Remove default metrics that have been passed in request.
     */
    protected function removeDefaultMetricsIfPresentInRequest()
    {
        foreach ($this->defaultMetrics as $key => $filter) {
            if ($this->request->has($key)) {
                $this->defaultMetrics = array_except($this->defaultMetrics, $key);
            }
        }
    }

    /**
     * Instantiates a filter.
     *
     * @param $filter
     * @return mixed
     */
    protected function resolveFilter($filter)
    {
        return new $this->metrics[$filter];
    }

    /**
     * Filter metrics that are only in the request.
     *
     * @param $metrics
     * @return mixed
     */
    protected function filterMetrics($metrics)
    {
        return array_filter($this->request->only(array_keys($this->metrics)));
    }
}