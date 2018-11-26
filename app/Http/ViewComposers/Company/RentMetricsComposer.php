<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Company\Metrics\CurrentMonthRentInvoicesMetric;

class RentMetricsComposer
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
            (new CurrentMonthRentInvoicesMetric)
                ->calculate(
                    request()->tenant()->rentInvoices()->getQuery()
                )->toArray()
        );
    }
}