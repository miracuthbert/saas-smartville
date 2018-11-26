<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 8/31/2018
 * Time: 8:01 PM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Utilities\Models\Utility;

class UtilitiesComposer
{
    /**
     * List of billing intervals.
     *
     * @var $billingIntervals
     */
    private $billingIntervals;

    /**
     * List of billing types.
     *
     * @var $billingTypes
     */
    private $billingTypes;

    /**
     * Bind plans to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if(!$this->billingIntervals) {
            $this->billingIntervals = Utility::$billingIntervals;
        }

        if(!$this->billingTypes) {
            $this->billingTypes = Utility::$billingTypes;
        }

        $view->with('billingIntervals', $this->billingIntervals)
            ->with('billingTypes', $this->billingTypes);
    }
}