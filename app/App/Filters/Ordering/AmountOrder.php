<?php

namespace Smartville\App\Filters\Ordering;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class AmountOrder extends FilterAbstract
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
        return $builder->orderBy('amount', $this->resolveOrderDirection($value));
    }
}
