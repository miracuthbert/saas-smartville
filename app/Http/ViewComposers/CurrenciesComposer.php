<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 7/28/2018
 * Time: 1:33 AM
 */

namespace Smartville\Http\ViewComposers;

use Illuminate\View\View;
use Smartville\Domain\Currencies\Models\Currency;

class CurrenciesComposer
{
    private $currencies;

    /**
     * Pass list of currencies to view.
     *
     * @param View $view
     */
    public function compose(View $view)
    {
        if (!$this->currencies) {
            $this->currencies = Currency::where('usable', true)->get();
        }

        $view->with('currencies', $this->currencies);
    }
}