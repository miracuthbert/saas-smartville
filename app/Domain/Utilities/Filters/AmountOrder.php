<?php

namespace Smartville\Domain\Utilities\Filters;

use Illuminate\Database\Eloquent\Builder;
use Smartville\App\Filters\FilterAbstract;

class AmountOrder extends FilterAbstract
{
    /**
     * Apply amount order filter.
     *
     * @param Builder $builder
     * @param $value
     *
     * @return mixed
     */
    public function filter(Builder $builder, $value)
    {
        return $builder->orderBy('price', $this->resolveOrderDirection($value))->orderByRaw(
            "utility_invoices.price * (utility_invoices.current - utility_invoices.previous) 
            {$this->resolveOrderDirection($value)}"
        );
    }
}
