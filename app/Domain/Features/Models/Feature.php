<?php

namespace Smartville\Domain\Features\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Feature extends Model
{
    use SoftDeletes,
        Sluggable,
        NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'overview',
        'description',
        'usable',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'edited_at',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['name'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    /**
     * Set current feature order.
     *
     * @param $request
     * @return bool|\Exception
     */
    public function setFeatureOrder($request)
    {
        try {
            // get order from request
            $order = $request->order;

            // get node(page) from request
            $node = Feature::find($request->node);

//            if (!$order || !$node) {
//                if (!$this->wasRecentlyCreated && $this->parent_id) {
//                    $this->saveAsRoot();
//                }
//                return true;
//            }

            if ($order == 'after') {
                $this->insertAfterNode($node);
                return true;
            }

            if ($order == 'before') {
                $this->insertBeforeNode($node);
                return true;
            }

            if ($order == 'child') {
                $node->appendNode($this);
                return true;
            }

            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * @param array|null $except
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function replicate(array $except = null)
    {
        $defaults = [
            $this->getParentIdName(),
            $this->getLftName(),
            $this->getRgtName(),
        ];

        $except = $except ? array_unique(array_merge($except, $defaults)) : $defaults;

        $instance = parent::replicate($except);
        (new SlugService())->slug($instance, true);

        return $instance;
    }
}
