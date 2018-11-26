<?php

namespace Smartville\App\Filters\Dates;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class ClearedFilter extends FilterAbstract
{
    /**
     * Database value mappings.
     *
     * @return array
     */
    public function mappings()
    {
        return DatesFilterMappings::mappings();
    }

    /**
     * Apply cleared date filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereBetween('cleared_at', $this->resolveFilterValue($value));
    }
}
