<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;

class PageTemplatesComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        $templates = collect(config('cms.templates'))->mapWithKeys(function ($item, $key) {
            return [
                $key => $key
            ];
        });

        return $view->with('templates', $templates);
    }
}