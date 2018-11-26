<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/2/2018
 * Time: 8:44 PM
 */

namespace Smartville\App\Traits\Eloquent\Deleteable;

use Illuminate\Database\Eloquent\Builder;

trait InTrashTrait
{
    /**
     * Scope a query to only include soft deleted properties.
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeInTrash(Builder $builder, $request)
    {
        if (!$request->has('trashed')) {
            return $builder;
        }

        return $builder->onlyTrashed();
    }
}