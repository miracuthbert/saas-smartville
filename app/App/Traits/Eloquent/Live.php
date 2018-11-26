<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 7/25/2018
 * Time: 3:52 AM
 */

namespace Smartville\App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;

trait Live
{
    public function scopeFinished(Builder $builder)
    {
        return $builder->where("{$this->getTable()}.live", '=', true);
    }
}