<?php

namespace Smartville\Http\Page;

use Smartville\Domain\Pages\Models\Page;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Page title.
     *
     * @var $pageTitle
     */
    public $pageTitle;

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Pages\Models\Page $page
     * @param array $parameters
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page, array $parameters)
    {
        $this->prepareTemplate($page, $parameters);

        $pageTitle = $this->pageTitle ?: $page->title;

        return view('pages.show', compact('page', 'pageTitle'));
    }

    /**
     * Setup the page's template.
     *
     * @param  \Smartville\Domain\Pages\Models\Page $page
     * @param array $parameters
     * @return void
     */
    protected function prepareTemplate(Page $page, array $parameters)
    {
        $templates = config('cms.templates');

        if (!$page->template || !isset($templates[$page->template])) {
            return;
        }

        $template = app($templates[$page->template]);

        $view = sprintf('templates.%s', $template->getView());

        if (!view()->exists($view)) {
            return;
        }

        $template->prepare($view = view($view)->with('page', $page), $parameters);

        $this->setPageTitle($template);

        $page->view = $view;
    }

    /**
     * Set page title based on template.
     *
     * @param $template
     */
    protected function setPageTitle($template)
    {
        $this->pageTitle = $template->pageTitle;
    }
}
