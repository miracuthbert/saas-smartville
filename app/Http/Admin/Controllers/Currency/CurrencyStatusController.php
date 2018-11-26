<?php

namespace Smartville\Http\Admin\Controllers\Currency;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Currencies\Models\Currency;

class CurrencyStatusController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  \Smartville\Domain\Currencies\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Currency $currency)
    {
        $status = $currency->usable == true ? false : true;

        $currency->update([
            'usable' => $status
        ]);

        $message = $status == true ? 'activated' : 'disabled';

        return back()->withSuccess("{$currency->name} is {$message}.");
    }
}
