<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/10/2018
 * Time: 8:01 PM
 */

namespace Smartville\App\Templates;

use Illuminate\View\View;
use Smartville\Domain\Subscriptions\Models\Plan;

class PricingTemplate extends AbstractTemplate
{
    /**
     * List of plans.
     *
     * @var Plan
     */
    protected $plans;

    /**
     * The name of the template's view.
     */
    protected $view = 'pricing';

    /**
     * PricingTemplate constructor.
     *
     * @param Plan $plans
     */
    public function __construct(Plan $plans)
    {
        $this->plans = $plans;
    }

    /**
     * @param View $view
     * @param array $parameters
     * @return mixed
     */
    public function prepare(View $view, array $parameters)
    {
        $plans = $this->plans->where('active', true)
            ->where('teams_enabled', true)
            ->get();

        $view->with('plans', $plans);
    }
}