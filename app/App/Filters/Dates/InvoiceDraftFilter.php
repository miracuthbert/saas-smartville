<?php

namespace Smartville\App\Filters\Dates;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class InvoiceDraftFilter extends FilterAbstract
{
    /**
     * Apply invoice draft filter filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->whereNull('sent_at');
    }
}
