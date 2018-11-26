<?php

namespace Smartville\Domain\Pages\Models;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;

class Page extends Model
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
        'title',
        'name',
        'uri',
        'body',
        'template',
        'usable',
        'parent_id',
        'hidden',
        'layout',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'prettyUri',
        'layoutPath'
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
            'uri' => [
                'source' => ['name'],
                'includeTrashed' => true,
                'maxLength' => 255,
            ]
        ];
    }

    /**
     * Set current page order.
     *
     * @param $request
     * @return bool|\Exception
     */
    public function setPageOrder($request)
    {
        try {
            // get order from request
            $order = $request->order;

            // get node(page) from request
            $node = Page::find($request->node);

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
     * Get page layout's path.
     *
     * @return string
     */
    public function getLayoutPathAttribute()
    {
        return 'layouts.' . $this->layout;
    }

    /**
     * Get page layout.
     *
     * @param $value
     * @return string
     */
    public function getLayoutAttribute($value)
    {
        if ($value) {
            return $value;
        }

        return config('cms.theme.layout');
    }

    /**
     * Get parsed page body as HTML.
     *
     * @return string
     */
    public function getBodyHtmlAttribute()
    {
        return (Markdown::convertToHtml($this->body));
    }

    /**
     * Get a pretty uri based on uri.
     *
     * @return string
     */
    public function getPrettyUriAttribute()
    {
        return '/' . ltrim($this->uri, '/');
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

    /**
     * Scope a query to only include visible pages.
     *
     * @param Builder $builder
     * @return Builder|static
     */
    public function scopeVisible(Builder $builder)
    {
        return $builder->where('hidden', false);
    }

    /**
     * Scope a query to only include live pages.
     *
     * @param Builder $builder
     * @return Builder|static
     */
    public function scopeLive(Builder $builder)
    {
        return $builder->where('usable', true);
    }
}
