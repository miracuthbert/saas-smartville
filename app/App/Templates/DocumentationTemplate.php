<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/10/2018
 * Time: 8:09 PM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;
use Smartville\Domain\Tutorials\Models\Tutorial;

class DocumentationTemplate extends AbstractTemplate
{
    /**
     * The name of the template's view.
     */
    protected $view = 'documentation.index';

    /**
     * List of tutorials.
     *
     * @var Tutorial
     */
    protected $tutorials;

    /**
     * DocumentationTemplate constructor.
     *
     * @param Tutorial $tutorials
     */
    public function __construct(Tutorial $tutorials)
    {
        $this->tutorials = $tutorials;
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
        $tutorials = $this->tutorials->with('parent')
            ->defaultOrder()
            ->usable()
            ->get()
            ->toTree();

        $selectedTutorials = $this->tutorials->with('parent')
            ->latest()
            ->whereHas('children')
            ->usable()
            ->limit(3)
            ->get();

        $view->with('tutorials', $tutorials)
            ->with('selectedTutorials', $selectedTutorials);
    }
}