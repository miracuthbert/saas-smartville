<?php

namespace Smartville\App\Filters\Dates;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class DueFilter extends FilterAbstract
{
    /**
     * Database value mappings.
     *
     * @return array
     */
    public function mappings()
    {
        return DatesFilterMappings::dueMappings();
    }

    /**
     * Apply due date filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereBetween('due_at', $this->resolveFilterValue($value));
    }
}
