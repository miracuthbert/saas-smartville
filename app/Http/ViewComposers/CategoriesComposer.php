<?php

namespace Smartville\Http\ViewComposers;

use Smartville\Domain\Categories\Models\Category;
use Illuminate\View\View;

class CategoriesComposer
{
    /**
     * Holds list of categories in storage.
     *
     * @var $categories
     */
    private $categories;

    private $properties_categories;

    public function compose(View $view)
    {
        if (!$this->categories) {
            $this->categories = Category::get()->toTree();

            $this->properties_categories = $this->categories->where('slug', 'properties')->first();
        }

        return $view->with('categories', $this->categories)
            ->with('property_categories', $this->properties_categories->children);
    }
}