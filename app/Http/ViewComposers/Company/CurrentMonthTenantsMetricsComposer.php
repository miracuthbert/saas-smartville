<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Company\Metrics\CurrentMonthTenancyMetrics;

class CurrentMonthTenantsMetricsComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        $metrics = (new CurrentMonthTenancyMetrics())->metrics();

        return $view->with($metrics);
    }
}