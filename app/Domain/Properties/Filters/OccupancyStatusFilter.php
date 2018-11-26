<?php

namespace Smartville\Domain\Properties\Filters;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class OccupancyStatusFilter extends FilterAbstract
{
    /**
     * Database where column mappings.
     *
     * @return array
     */
    public function whereMappings()
    {
        return [
            'occupied' => 'whereNotNull',
            'vacant' => 'whereNull',
        ];
    }

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
        if ($value == null) {
            return $builder;
        }

        // resolve where
        $where = $this->resolveWhereClause($value);

        return $builder->{$where}('occupied_at');
    }
}
