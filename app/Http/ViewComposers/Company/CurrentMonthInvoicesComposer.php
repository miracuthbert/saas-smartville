<?php

namespace Smartville\Http\ViewComposers\Company;

use Carbon\Carbon;
use Illuminate\View\View;

class CurrentMonthInvoicesComposer
{
    /**
     * Share data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $company = request()->tenant();
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();

        $rentInvoices = cache()->remember(
            'rentInvoices',
            10,
            function () use ($endDate, $startDate, $company) {
                return $company->rentInvoices()
                    ->with('user', 'property', 'payments')
                    ->whereDate('due_at', '>=', $startDate)
                    ->whereDate('due_at', '<=', $endDate)
                    ->orderByDesc('due_at')->take(7)->get();
            });

        $utilityInvoices = cache()->remember(
            'utilityInvoices',
            10,
            function () use ($endDate, $startDate, $company) {
                return $company->utilityInvoices()
                    ->with('user', 'property', 'utility', 'payments')
                    ->whereDate('due_at', '>=', $startDate)
                    ->whereDate('due_at', '<=', $endDate)
                    ->orderByDesc('due_at')->take(7)->get();
            });

        return $view->with([
            'rentInvoices' => $rentInvoices,
            'utilityInvoices' => $utilityInvoices,
        ]);
    }
}