<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/13/2018
 * Time: 4:09 AM
 */

namespace Smartville\Domain\Company\Metrics;

class CurrentMonthTenancyMetrics
{
    /**
     * Get current month tenancy metrics.
     *
     * @return array
     */
    public function metrics()
    {
        $metrics = array_merge(
            (new CurrentMonthNewTenantsMetric())->metric(),
            (new CurrentMonthVacatedTenantsMetric())->metric(),
            (new CurrentWeekTenantsMetric())->metric()
        );

        return $metrics;
    }
}