<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;

class LeaseFiltersComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with('dummy', collect());
    }
}