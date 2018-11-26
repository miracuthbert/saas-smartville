<?php

namespace Smartville\Http\Admin\Controllers\Currency;

use Smartville\Domain\Currencies\Models\Currency;
use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Http\Admin\Requests\CurrencyStoreRequest;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currencies = Currency::orderByDesc('usable')->paginate();

        return view('admin.currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CurrencyStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CurrencyStoreRequest $request)
    {
        $currency = new Currency;
        $currency->fill($request->only('cc', 'symbol', 'name', 'usable'));
        $currency->save();

        return redirect()->route('admin.currencies.index')->withSuccess("{$currency->name} successfully added.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \Smartville\Domain\Currencies\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function show(Currency $currency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Smartville\Domain\Currencies\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Currency $currency)
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CurrencyStoreRequest $request
     * @param  \Smartville\Domain\Currencies\Models\Currency $currency
     * @return \Illuminate\Http\Response
     */
    public function update(CurrencyStoreRequest $request, Currency $currency)
    {
        $currency->fill($request->only('cc', 'symbol', 'name', 'usable'));
        $currency->save();

        return back()->withSuccess('Currency successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Smartville\Domain\Currencies\Models\Currency $currency
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Currency $currency)
    {
        try {
            $currency->delete();
        } catch (\Exception $e) {
            return back()->withError("Failed deleting `{$currency->name}` from currencies.");
        }

        return back()->withSuccess("`{$currency->name}` successfully deleted from currencies.");
    }
}
