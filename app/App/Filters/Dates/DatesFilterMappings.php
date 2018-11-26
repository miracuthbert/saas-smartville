<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 9/15/2018
 * Time: 5:40 PM
 */

namespace Smartville\App\Filters\Dates;

class DatesFilterMappings
{
    /**
     * A list of common date filters map.
     *
     * @return array
     */
    public static function mappings()
    {
        $now = now();

        return [
            'today' => [$now->copy()->startOfDay()->toDateString(), $now->copy()->endOfDay()->toDateString()],
            'week' => [$now->copy()->startOfWeek()->toDateString(), $now->copy()->endOfWeek()->toDateString()],
            'month' => [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()],
            'year' => [$now->copy()->startOfYear()->toDateString(), $now->copy()->endOfYear()->toDateString()],
        ];
    }

    /**
     * A list of due date filters map.
     *
     * @return array
     */
    public static function dueMappings()
    {
        $now = now();

        return [
            'last_week' => [$now->copy()->previous()->startOfWeek()->toDateString(), $now->copy()->previous()->endOfWeek()->toDateString()],
            'today' => [$now->copy()->startOfDay()->toDateString(), $now->copy()->endOfDay()->toDateString()],
            'week' => [$now->copy()->startOfWeek()->toDateString(), $now->copy()->endOfWeek()->toDateString()],
            'next_week' => [$now->copy()->next()->startOfWeek()->toDateString(), $now->copy()->next()->endOfWeek()->toDateString()],
            'month' => [$now->copy()->startOfMonth()->toDateString(), $now->copy()->endOfMonth()->toDateString()],
        ];
    }
}