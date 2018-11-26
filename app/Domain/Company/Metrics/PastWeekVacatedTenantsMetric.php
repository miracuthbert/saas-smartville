<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/14/2018
 * Time: 4:49 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;

class PastWeekVacatedTenantsMetric
{
    public $tenant;

    /**
     * PastWeekVacatedTenantsMetric constructor.
     */
    public function __construct()
    {
        $this->tenant = request()->tenant();
    }

    /**
     * Get past week number of vacated tenants.
     *
     * @return array
     */
    public function metric()
    {
        $pastWeek = Carbon::now()->previousWeekendDay();

        $count = $this->tenant->leases()
            ->whereNotNull('vacated_at')
            ->whereDate('vacated_at', '>=', $pastWeek->copy()->startOfWeek()->toDateString())
            ->whereDate('vacated_at', '<=', $pastWeek->copy()->endOfWeek()->toDateString())
            ->count();

        return ['vacated_tenants_past_week_count' => $count];
    }
}