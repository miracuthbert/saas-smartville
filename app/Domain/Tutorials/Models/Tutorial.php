<?php

namespace Smartville\Domain\Tutorials\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Smartville\App\Traits\Eloquent\UsableTrait;

class Tutorial extends Model
{
    use SoftDeletes,
        Sluggable,
        NodeTrait,
        UsableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'overview',
        'body',
        'usable',
        'parent_id',
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
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => ['parent.title', 'title'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    /**
     * Set current tutorial order.
     *
     * @param $request
     * @return bool|\Exception
     */
    public function setTutorialOrder($request)
    {
        try {
            // get order from request
            $order = $request->order;

            // get node(page) from request
            $node = Tutorial::find($request->node);

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
