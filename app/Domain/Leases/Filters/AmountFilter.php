<?php

namespace Smartville\Domain\Leases\Filters;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class AmountFilter extends FilterAbstract
{
    /**
     * Apply filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->where('amount', $value);
    }
}
