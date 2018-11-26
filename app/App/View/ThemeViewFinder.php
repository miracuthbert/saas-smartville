<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/4/2018
 * Time: 1:44 PM
 */

namespace Smartville\App\View;

use Illuminate\View\FileViewFinder;

class ThemeViewFinder extends FileViewFinder
{
    protected $activeTheme;

    protected $basePath;

    /**
     * Sets base path.
     *
     * @param mixed $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Sets active theme.
     *
     * @param mixed $activeTheme
     */
    public function setActiveTheme($activeTheme)
    {
        $this->activeTheme = $activeTheme;

        array_unshift($this->paths, $this->basePath . '/' . $this->activeTheme . '/views');
    }
}