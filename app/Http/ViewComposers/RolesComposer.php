<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 2/16/2018
 * Time: 7:45 PM
 */

namespace Smartville\Http\ViewComposers;

use Smartville\Domain\Users\Models\Role;
use Illuminate\View\View;

class RolesComposer
{
    private $roleables;

    /**
     * Share list of roles.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        if (!$this->roleables) {
            $this->roleables = Role::get()->toTree();
        }

        $view->with('roleables', $this->roleables);
    }
}