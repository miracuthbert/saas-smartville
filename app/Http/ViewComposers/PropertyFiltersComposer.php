<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Properties\Filters\PropertyFilters;

class PropertyFiltersComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with('filters_mappings', PropertyFilters::mappings());
    }
}