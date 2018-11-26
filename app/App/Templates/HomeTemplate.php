<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/9/2018
 * Time: 1:52 AM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;
use Smartville\Domain\Features\Models\Feature;

class HomeTemplate extends AbstractTemplate
{
    /**
     * List of features.
     *
     * @var Feature
     */
    protected $features;

    /**
     * The name of the template's view.
     */
    protected $view = 'home';

    /**
     * HomeTemplate constructor.
     *
     * @param Feature $features
     */
    public function __construct(Feature $features)
    {
        $this->features = $features;
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
        $features = $this->features->with('parent')
            ->defaultOrder()
            ->where('usable', true)
            ->take(6)
            ->get()
            ->toFlatTree();

        $view->with('features', $features);
    }
}