<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/10/2018
 * Time: 8:31 PM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;

class SupportTemplate extends AbstractTemplate
{
    /**
     * The name of the template's view.
     */
    protected $view = 'support.index';

    /**
     * Handles the view instance.
     *
     * @param View $view
     * @param array $parameters
     * @return mixed
     */
    public function prepare(View $view, array $parameters)
    {
        $docs = collect();

        $view->with('docs', $docs);
    }
}