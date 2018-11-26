<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/1/2018
 * Time: 4:38 PM
 */

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Utilities\Models\Utility;

class CompanyUtilitiesComposer
{
    /**
     * Implements list of utilities.
     *
     * @var $utilities
     */
    private $utilities;

    /**
     * Bind plans to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if(!$this->utilities) {
            $this->utilities = Utility::active()->get();
        }

        $view->with('utilities', $this->utilities);
    }
}