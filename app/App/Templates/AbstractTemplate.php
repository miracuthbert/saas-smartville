<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/5/2018
 * Time: 7:03 PM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;

abstract class AbstractTemplate
{
    /**
     * The name of the template's view.
     */
    protected $view;

    /**
     * The title to be rendered with the view.
     */
    public $pageTitle;

    /**
     * Set's the page title.
     *
     * @param mixed $pageTitle
     */
    public function setPageTitle($pageTitle): void
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * Handles the view instance.
     *
     * @param View $view
     * @param array $parameters
     * @return mixed
     */
    abstract public function prepare(View $view, array $parameters);

    /**
     * Returns view property.
     *
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }
}