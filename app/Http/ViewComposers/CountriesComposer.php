<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 3/9/2018
 * Time: 11:29 AM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Countries\Models\Country;

class CountriesComposer
{
    /**
     * Implements countries collection.
     *
     * @var $countries
     */
    private $countries;

    /**
     * Bind countries to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if (!$this->countries) {
            $this->countries = Country::orderBy('code', 'asc')->get();
        }

        $view->with('countries', $this->countries);
    }
}