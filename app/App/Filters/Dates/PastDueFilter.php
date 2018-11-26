<?php

namespace Smartville\App\Filters\Dates;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class PastDueFilter extends FilterAbstract
{
    /**
     * Apply past due filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereDate('due_at', '<', Carbon::now()->toDateString());
    }
}
