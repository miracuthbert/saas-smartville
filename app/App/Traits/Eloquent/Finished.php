<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 7/25/2018
 * Time: 3:38 AM
 */

namespace Smartville\App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait Finished
{
    /**
     * Scope a query to only include finished models.
     *
     * @param Builder $builder
     * @return Builder
     */
    public function scopeFinished(Builder $builder)
    {
        return $builder->where("{$this->getTable()}.finished", '=', true);
    }
}