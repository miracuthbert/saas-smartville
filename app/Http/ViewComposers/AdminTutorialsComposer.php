<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Tutorials\Models\Tutorial;

class AdminTutorialsComposer
{
    /**
     * List of tutorials.
     *
     * @var $tutorials
     */
    private $tutorials;

    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        if (!$this->tutorials) {
            $this->tutorials = Tutorial::withDepth()->defaultOrder()->with('parent')->get()->toFlatTree();
        }

        return $view->with('tutorials', $this->tutorials);
    }
}