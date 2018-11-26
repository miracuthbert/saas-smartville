<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/14/2018
 * Time: 4:39 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;

class CurrentMonthNewTenantsMetric
{
    public $tenant;

    /**
     * CurrentMonthNewTenantsMetric constructor.
     */
    public function __construct()
    {
        $this->tenant = request()->tenant();
    }

    /**
     * Get this month's number of new tenants.
     *
     * @return array
     */
    public function metric()
    {
        $count = $this->tenant->leases()
            ->whereDate('moved_in_at', '>=', Carbon::now()->startOfMonth()->toDateString())
            ->whereDate('moved_in_at', '<=', Carbon::now()->endOfDay()->toDateString())
            ->count();

        return ['new_tenants_count' => $count];
    }
}