<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/14/2018
 * Time: 4:46 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;

class CurrentMonthVacatedTenantsMetric
{
    public $tenant;

    /**
     * CurrentMonthVacatedTenantsMetric constructor.
     */
    public function __construct()
    {
        $this->tenant = request()->tenant();
    }

    /**
     * Get this month's number of vacated tenants.
     *
     * @return array
     */
    public function metric()
    {
        $count = $this->tenant->leases()
            ->whereNotNull('vacated_at')
            ->whereDate('vacated_at', '>=', Carbon::now()->startOfMonth()->toDateString())
            ->whereDate('vacated_at', '<=', Carbon::now()->endOfDay()->toDateString())
            ->count();

        return ['vacated_tenants_count' => $count];
    }
}