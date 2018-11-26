<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/11/2018
 * Time: 12:47 AM
 */

namespace Smartville\App\HashIds\Traits;

use Smartville\App\HashIds\Observers\ModelHashIdObserver;

trait UsesHashIds
{
    /**
     * Boot trait when model boots.
     *
     * @return void
     */
    public static function bootUsesHashIds()
    {
        static::observe(new ModelHashIdObserver());

        static::creating(function ($model) {
            $model->fillable(array_merge($model->fillable, ['hash_id']));
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'hash_id';
    }

    /**
     * Get the identity for the entity.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->hash_id;
    }

    /**
     * Get the hash name from model.
     *
     * @return string
     */
    public function getHashShortName()
    {
        // get model name
        $name = snake_case((new \ReflectionClass($this))->getShortName());

        // loop through names
        $names = collect(explode('_', $name))->map(function ($item) {
            return str_limit($item, 4, '');
        });

        $name = str_limit($name, 4, '');

        // assign looped through name if more than one
        if (count($names) > 1) {
            $name = join('_', $names->all());
        }

        return strtolower($name);
    }
}