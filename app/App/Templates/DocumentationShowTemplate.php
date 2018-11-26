<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 11/26/2018
 * Time: 12:36 PM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;
use Smartville\Domain\Tutorials\Models\Tutorial;

class DocumentationShowTemplate extends AbstractTemplate
{
    /**
     * The name of the template's view.
     */
    protected $view = 'documentation.show';

    /**
     * Instance of tutorial.
     *
     * @var Tutorial
     */
    protected $tutorial;

    /**
     * DocumentationShowTemplate constructor.
     *
     * @param Tutorial $tutorial
     */
    public function __construct(Tutorial $tutorial)
    {
        $this->tutorial = $tutorial;
    }

    /**
     * Handles the view instance.
     *
     * @param View $view
     * @param array $parameters
     * @return mixed
     */
    public function prepare(View $view, array $parameters)
    {
        $tutorial = $this->tutorial->with(
            'parent.children.children',
            'children.children.children'
        )->where('slug', $parameters['slug'])->first();

        $next = $tutorial->getNextSibling();
        $prev = $tutorial->getPrevSibling();

        $this->setPageTitle($tutorial->title);

        $view->with('tutorial', $tutorial)
            ->with('nextTutorial', $next)
            ->with('prevTutorial', $prev);
    }
}