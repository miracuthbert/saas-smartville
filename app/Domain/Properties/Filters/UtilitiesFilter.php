<?php

namespace Smartville\Domain\Properties\Filters;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class UtilitiesFilter extends FilterAbstract
{
    /**
     * Apply utilities filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereHas('utilities', function (Builder $builder) use ($value) {
            return $builder->where('slug', $value);
        });
    }
}
