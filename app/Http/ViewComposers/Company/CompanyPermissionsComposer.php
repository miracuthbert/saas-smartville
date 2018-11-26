<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Users\Models\Permission;

class CompanyPermissionsComposer
{
    /**
     * Fetch a list of company based permissions.
     *
     * @var $permissions
     */
    private $permissions;

    /**
     * Share list of company based permissions.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        $this->permissions = Permission::where('usable', true)
            ->forCompany()
            ->get();

        return $view->with('permissions', $this->permissions);
    }
}