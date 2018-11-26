<?php

namespace Smartville\Domain\Utilities\Filters;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class UtilityFilter extends FilterAbstract
{
    /**
     * Apply utility filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereHas('utility', function(Builder $builder) use ($value) {
           return $builder->where('slug', $value);
        });
    }
}
