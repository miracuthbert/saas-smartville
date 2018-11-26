<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/15/2018
 * Time: 4:49 AM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Leases\Filters\LeaseInvoiceFilters;

class LeaseInvoiceFiltersComposer
{
    /**
     * Pass filter mappings to view.
     *
     * @param View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with('filters_mappings', LeaseInvoiceFilters::mappings());
    }
}