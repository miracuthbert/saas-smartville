<?php

namespace Smartville\App\Filters\Tenant;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class TenantFilter extends FilterAbstract
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
        if ($value === null) {
            return $builder;
        }

        $names = explode(' ', $value);
        $wheres = [];

        foreach ($names as $name) {
            if (empty($wheres)) {
                $where = [
                    ['first_name', 'like', '%' . $name . '%'],
                    ['last_name', 'like', '%' . $name . '%', 'or']
                ];
            } else {
                $where = [
                    ['first_name', 'like', '%' . $name . '%', 'or'],
                    ['last_name', 'like', '%' . $name . '%', 'or']
                ];
            }

            $wheres = array_merge($wheres, $where);
        }

        $builder->whereHas('user', function (Builder $builder) use ($wheres, $value) {
            return $builder->where($wheres);
        });
    }
}
