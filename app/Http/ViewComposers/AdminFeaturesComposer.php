<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Features\Models\Feature;

class AdminFeaturesComposer
{
    /**
     * List of features.
     *
     * @var $features
     */
    private $features;

    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        if (!$this->features) {
            $this->features = Feature::with('parent')->defaultOrder()->get()->toFlatTree();
        }

        return $view->with('features', $this->features);
    }
}