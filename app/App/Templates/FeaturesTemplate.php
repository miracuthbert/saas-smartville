<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/9/2018
 * Time: 2:28 AM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;
use Smartville\Domain\Features\Models\Feature;

class FeaturesTemplate extends AbstractTemplate
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
    protected $view = 'features';

    /**
     * FeaturesTemplate constructor.
     *
     * @param Feature $features
     */
    public function __construct(Feature $features)
    {
        $this->features = $features;
    }

    /**
     * @param View $view
     * @param array $parameters
     * @return mixed
     */
    public function prepare(View $view, array $parameters)
    {
        $features = $this->features->with('parent')
            ->defaultOrder()
            ->where('usable', true)
            ->get()
            ->toFlatTree();

        $view->with('features', $features);
    }
}