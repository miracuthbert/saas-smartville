<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/14/2018
 * Time: 4:43 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;

class PastWeekNewTenantsMetric
{
    public $tenant;

    /**
     * PastWeekNewTenantsMetric constructor.
     */
    public function __construct()
    {
        $this->tenant = request()->tenant();
    }

    /**
     * Get past week number of new tenants.
     *
     * @return array
     */
    public function metric()
    {
        $pastWeek = Carbon::now()->previousWeekendDay();

        $count = $this->tenant->leases()
            ->whereDate('moved_in_at', '>=', $pastWeek->copy()->startOfWeek()->toDateString())
            ->whereDate('moved_in_at', '<=', $pastWeek->copy()->endOfWeek()->toDateString())
            ->count();

        return ['new_tenants_past_week_count' => $count];
    }
}