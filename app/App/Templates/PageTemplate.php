<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/6/2018
 * Time: 1:12 AM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;

class PageTemplate extends AbstractTemplate
{
    /**
     * The name of the template's view.
     */
    protected $view = 'page';

    /**
     * Handles the view instance.
     *
     * @param View $view
     * @param array $parameters
     * @return mixed
     */
    public function prepare(View $view, array $parameters)
    {
        // TODO: Implement prepare() method.
    }
}