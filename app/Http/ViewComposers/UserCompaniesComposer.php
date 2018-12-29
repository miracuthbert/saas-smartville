<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 4/28/2018
 * Time: 4:40 PM
 */

namespace Smartville\Http\ViewComposers;

use Carbon\Carbon;
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
            $this->companies = auth()->check() ?
                auth()->user()->companies()->whereHas('users.companyRoles', function ($query) {
                    return $query->where('user_id', auth()->user()->id)
                        ->whereNull('expires_at')
                        ->orWhere('expires_at', '>', Carbon::now());
                })->get()
                : collect([]);
        }

        $view->with('user_companies', $this->companies);
    }
}