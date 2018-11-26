<?php

namespace Smartville\App\Filters\Ordering;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class SentOrder extends FilterAbstract
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
        return $builder->orderBy('sent_at', $this->resolveOrderDirection($value));
    }
}
