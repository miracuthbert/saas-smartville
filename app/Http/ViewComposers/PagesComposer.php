<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Pages\Models\Page;

class PagesComposer
{
    /**
     * List of active pages.
     *
     * @var $pages
     */
    private $pages;

    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        if (!$this->pages) {
            $this->pages = Page::withDepth()->defaultOrder()->with('parent')
                ->live()
                ->visible()
                ->get(['name', 'slug'])
                ->toTree();
        }

        return $view->with('pages', $this->pages);
    }
}