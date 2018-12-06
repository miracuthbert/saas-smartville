<?php
/**
 * Created by PhpStorm.
 * User: Cuthbert Mirambo
 * Date: 10/4/2018
 * Time: 3:34 PM
 */

if (!function_exists('theme')) {
    /**
     * Get a key value from theme settings.
     *
     * @param null $key
     * @param null $default
     * @return \Illuminate\Foundation\Application|mixed
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    function theme($key = null, $default = null) {
        if ($key === null) {
            return app(\Smartville\App\View\ThemeSettings::class);
        }

        return app(\Smartville\App\View\ThemeSettings::class)->get($key, $default);
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Generate a theme based asset path for the application.
     *
     * @param  string $path
     * @return string
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    function theme_asset($path)
    {
        $config = app('config')->get('cms.theme');

        return app('url')->asset($config['folder'] . '/' . $config['active'] . '/' . $path);
    }
}

if (!function_exists('paddedNestedString')) {
    /**
     * Generate a padded string based on its nest depth.
     *
     * @param $depth
     * @return string
     */
    function paddedNestedString($depth)
    {
        $padding = str_repeat('&nbsp', $depth * 4);

        return $padding;
    }
}
