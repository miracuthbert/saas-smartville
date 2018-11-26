<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/16/2018
 * Time: 4:04 AM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Utilities\Filters\UtilityInvoiceFilters;

class UtilityInvoiceFiltersComposer
{
    /**
     * Pass filter mappings to view.
     *
     * @param View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with('filters_mappings', UtilityInvoiceFilters::mappings());
    }
}