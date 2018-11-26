<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 8/9/2018
 * Time: 8:19 PM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Amenities\Models\Amenity;

class AmenitiesComposer
{
    /**
     * Implements list of amenities.
     *
     * @var $amenities
     */
    private $amenities;

    /**
     * Bind plans to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        if(!$this->amenities) {
            $this->amenities = Amenity::active()->get();
        }

        $view->with('amenities', $this->amenities);
    }
}