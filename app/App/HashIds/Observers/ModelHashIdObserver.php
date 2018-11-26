<?php

namespace Smartville\App\HashIds\Observers;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;

class ModelHashIdObserver
{
    public function created(Model $model)
    {
        $model->update([
            'hash_id' => config('app.short_name') . '_' . $model->getHashShortName() . '_' . Hashids::encode($model->id)
        ]);
    }
}
