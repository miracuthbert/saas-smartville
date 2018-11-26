<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/10/2018
 * Time: 6:24 PM
 */

namespace Smartville\App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait UsableTrait
{
    /**
     * Scope a query to include only usable models.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeUsable(Builder $builder)
    {
        return $builder->where("{$this->getTable()}.usable", '=', true);
    }
    /**
     * Scope a query to include only unusable models.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeDisabled(Builder $builder)
    {
        return $builder->where("{$this->getTable()}.usable", '=', false);
    }
}