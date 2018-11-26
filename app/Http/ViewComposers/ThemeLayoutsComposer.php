<?php

namespace Smartville\Http\ViewComposers;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class ThemeLayoutsComposer
{
    /**
     * Pass data to view.
     *
     * @param \Illuminate\View\View $view
     * @return View
     */
    public function compose(View $view)
    {
//        $paths = app('theme.finder')->getPaths();
        $cms = config('cms.theme');

        $layouts = $cms['folder'] . '/' . $cms['active'] . '/views/layouts';
        $cmsPath = resource_path($layouts);

        $layouts = collect(File::files($cmsPath, true))->mapWithKeys(function ($layout, $key) {
            $name = str_replace(".blade.php", '', $layout->getFilename());

            return [$name => $name];
        });

        return $view->with('layouts', $layouts);
    }
}