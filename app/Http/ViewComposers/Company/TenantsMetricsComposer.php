<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;

class TenantsMetricsComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        // you can also pass data in separate lines as shown below
        // $view->with('data', collect())
        // $view->with('dummy', collect())
        // todo: don't forget to clear these comments when done
        // todo: remember to register the view composer in the composer service provider

        // todo: replace data below with your own
        return $view->with([
            'data' => collect(),
            'dummy' => collect(),
        ]);
    }
}