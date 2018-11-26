<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/1/2018
 * Time: 4:47 PM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Metrics\MetricAbstract;
use Smartville\App\Money\Money;

class CurrentMonthUtilityInvoicesMetric extends MetricAbstract
{
    /**
     * Calculate metric.
     *
     * @param Builder $builder
     * @return mixed
     */
    public function calculate(Builder $builder)
    {
        $builder = $builder->whereDate('due_at', '>=', Carbon::now()->startOfMonth()->toDateString())
            ->whereDate('due_at', '<=', Carbon::now()->endOfMonth()->toDateString());

        $utilityInvoicesCount = $builder->count();
        $utilityInvoicesClearedCount = $builder->whereNotNull('cleared_at')->count();
        $utilityInvoicesClearedTotal = $builder->get(['utility_invoices.*'])->map(function ($invoice) {
            return $invoice->initialAmount;
        })->sum();

        return collect([
            'utility_invoices_count' => $utilityInvoicesCount,
            'utility_invoices_cleared_count' => $utilityInvoicesClearedCount,
            'utility_invoices_cleared_total' => (new Money($utilityInvoicesClearedTotal, request()->tenant()->currency))->formatted(),
            'utility_invoices_clearance_rate' => $utilityInvoicesCount != 0 ? round(((($utilityInvoicesClearedCount / $utilityInvoicesCount) || 0) * 100), 2) : 0,
        ]);
    }
}