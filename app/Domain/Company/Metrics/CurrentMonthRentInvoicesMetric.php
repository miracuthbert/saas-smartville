<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/1/2018
 * Time: 2:34 PM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Metrics\MetricAbstract;
use Smartville\App\Money\Money;

class CurrentMonthRentInvoicesMetric extends MetricAbstract
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

        $rentInvoicesCount = $builder->count();
        $rentInvoicesClearedCount = $builder->whereNotNull('cleared_at')->count();
        $rentInvoicesClearedTotal = $builder->sum('amount');

        return collect([
            'rent_invoices_count' => $rentInvoicesCount,
            'rent_invoices_cleared_count' => $rentInvoicesClearedCount,
            'rent_invoices_cleared_total' => (new Money($rentInvoicesClearedTotal, request()->tenant()->currency))->formatted(),
            'rent_invoices_clearance_rate' => $rentInvoicesCount != 0 ? round(((($rentInvoicesClearedCount / $rentInvoicesCount)) * 100), 2) : 0,
        ]);
    }
}