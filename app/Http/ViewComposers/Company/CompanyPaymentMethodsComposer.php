<?php

namespace Smartville\Http\ViewComposers\Company;

use Illuminate\View\View;
use Smartville\Domain\Company\Models\CompanyPaymentMethod;

class CompanyPaymentMethodsComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
        return $view->with([
            'payment_methods' => CompanyPaymentMethod::usable()->get(),
        ]);
    }
}