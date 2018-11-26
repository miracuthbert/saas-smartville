<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 7/23/2018
 * Time: 6:10 PM
 */

namespace Smartville\Domain\Properties\Observers;

use Illuminate\Database\Eloquent\Model;
use Smartville\App\Tenant\Observers\TenantObserver;
use Smartville\Domain\Categories\Models\Category;

class PropertyObserver extends TenantObserver
{
    /**
     * Listen to given tenant model creating event.
     *
     * @param Model $model
     */
    public function creating(Model $model)
    {
        if (!isset($model->category_id)) {
            $model->category()->associate(Category::first()->id);
        }

        parent::creating($model);
    }

    /**
     * Listen to given tenant model created event.
     *
     * @param Model $model
     */
    public function created(Model $model)
    {
        $model->slug = null;
        $model->save();

        // todo: move this to database or a file
        $features = [
            [
                'name' => 'Bedroom',
                'count' => 1,
            ],
            [
                'name' => 'Bathroom',
                'count' => 1,
            ],
        ];

        $model->features()->createMany($features);
    }
}