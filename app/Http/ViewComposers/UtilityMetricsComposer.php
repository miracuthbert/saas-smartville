<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Company\Metrics\CurrentMonthUtilityInvoicesMetric;

class UtilityMetricsComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with(
            (new CurrentMonthUtilityInvoicesMetric)
                ->calculate(request()->tenant()->utilityInvoices()->getQuery())
                ->toArray()
        );
    }
}