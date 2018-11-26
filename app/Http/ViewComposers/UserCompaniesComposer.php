<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 4/28/2018
 * Time: 4:40 PM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;

class UserCompaniesComposer
{
    private $companies;

    /**
     * Share list of user companies.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        if (!$this->companies) {
            $this->companies = auth()->check() ? auth()->user()->companies : collect([]);
        }

        $view->with('user_companies', $this->companies);
    }
}