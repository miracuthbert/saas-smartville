<?php

namespace Smartville\Domain\Properties\Filters;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class StatusFilter extends FilterAbstract
{
    /**
     * Database value mappings.
     *
     * @return array
     */
    public function mappings()
    {
        return [
            'live' => true,
            'disabled' => false,
        ];
    }

    /**
     * Apply status filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        if ($value === null) {
            return $builder;
        }

        return $builder->where('live', $this->resolveFilterValue($value));
    }
}
