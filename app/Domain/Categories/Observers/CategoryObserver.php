<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 7/23/2018
 * Time: 6:37 PM
 */

namespace Smartville\Domain\Categories\Observers;

use Smartville\Domain\Categories\Models\Category;

class CategoryObserver
{
    /**
     * Listen to resource 'creating' event.
     *
     * @param Category $category
     */
    public function creating(Category $category)
    {
        $prefix = $category->parent ? $category->parent->name . ' ' : '';
        $category->slug = str_slug($prefix . $category->name);

        $category->usable = $category->parent ? true : false;
    }
}