<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/14/2018
 * Time: 4:35 AM
 */

namespace Smartville\Domain\Company\Metrics;

use Carbon\Carbon;

class CurrentWeekTenantsMetric
{
    public $tenant;

    /**
     * CurrentWeekTenantsMetric constructor.
     */
    public function __construct()
    {
        $this->tenant = request()->tenant();
    }

    /**
     * Get weeks new tenants count.
     *
     * @return array
     */
    public function metric()
    {
        $metrics = collect();

        $startOfWeek = Carbon::now()->startOfWeek();

        $newTenants = $this->tenant->leases()
            ->whereDate('moved_in_at', '>=', $startOfWeek->copy()->toDateString())
            ->whereDate('moved_in_at', '<=', Carbon::today()->toDateString())
            ->get();

        $newTenantsCount = $newTenants->count();

        $vacatedTenants = $this->tenant->leases()
            ->whereDate('vacated_at', '>=', $startOfWeek->copy()->toDateString())
            ->whereDate('vacated_at', '<=', Carbon::today()->toDateString())
            ->get();

        $vacatedTenantsCount = $vacatedTenants->count();

        $newTenantsGrouped = $newTenants->toArray();

        $newTenantsGroupedByDay = collect($newTenantsGrouped)->groupBy(function ($item, $key) {
            return Carbon::parse($item['moved_in_at'])->format('l');
        })->toArray();

        $newTenantsMetricsMap = collect($newTenantsGroupedByDay)->mapWithKeys(function ($items, $key) {
            return [$key => count($items)];
        });

        $vacatedTenantsGrouped = $vacatedTenants->toArray();

        $vacatedTenantsGroupedByDay = collect($vacatedTenantsGrouped)->groupBy(function ($item, $key) {
            return Carbon::parse($item['vacated_at'])->format('l');
        })->toArray();

        $vacatedTenantsMetricsMap = collect($vacatedTenantsGroupedByDay)->mapWithKeys(function ($items, $key) {
            return [$key => count($items)];
        });

        $diffInDays = $startOfWeek->copy()->diffInDays($startOfWeek->copy()->endOfWeek());
        $x = 0;

        while ($x <= $diffInDays) {
            $day = $x == 0 ?
                $startOfWeek->copy()->format('l') :
                $startOfWeek->copy()->addDays($x)->format('l');

            $metrics->put($day, [
                'new' => [
                    'count' => $newTenantsMetricsMap->get($day, 0),
                    'rate' => $newTenantsCount > 0 ? round((($newTenantsMetricsMap->get($day, 0) / $newTenantsCount) * 100), 2) : 0,
                ],
                'vacated' => [
                    'count' => $vacatedTenantsMetricsMap->get($day, 0),
                    'rate' => $vacatedTenantsCount > 0 ? round((($vacatedTenantsMetricsMap->get($day, 0) / $vacatedTenantsCount) * 100), 2) : 0,
                ],
            ]);

            $x++;
        };

        $collection = [
            'current_week_vacated_tenants_count' => $vacatedTenantsCount,
            'current_week_new_tenants_count' => $newTenantsCount,
            'current_week_tenants' => $metrics->all(),
        ];

        return $collection;
    }
}