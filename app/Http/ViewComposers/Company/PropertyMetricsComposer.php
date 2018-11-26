<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Company\Metrics\CurrentMonthPropertiesOccupancyRateMetric;
use Smartville\Domain\Properties\Models\Property;

class PropertyMetricsComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        $metrics = (new CurrentMonthPropertiesOccupancyRateMetric())->calculate(Property::query());

        return $view->with($metrics->toArray());
    }
}