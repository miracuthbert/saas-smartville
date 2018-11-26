<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Company\Models\CompanyRole;

class CompanyRolesComposer
{
    /**
     * List of company roles.
     *
     * @var $roles
     */
    private $roles;

    /**
     * Share company roles in view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        if (!$this->roles) {
            $this->roles = CompanyRole::where('usable', true)->get();
        }

        return $view->with('roles', $this->roles);
    }
}