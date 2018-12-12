<?php

namespace Smartville\App\Providers;

use Illuminate\Support\ServiceProvider;
use Smartville\App\View\ThemeSettings;
use Smartville\App\View\ThemeViewFinder;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['view']->setFinder($this->app['theme.finder']);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ThemeSettings::class, function () {
            return ThemeSettings::make(storage_path('app/smartville/settings/theme.json'));
        });

        $this->app->singleton('theme.finder', function ($app) {
            $finder = new ThemeViewFinder($app['files'], $app['config']['view.paths']);

            $config = $app['config']['cms.theme'];

            $finder->setBasePath($app['path.resources'] . '/' . $config['folder']);
            $finder->setActiveTheme(theme('current', $config['active']));

            return $finder;
        });
    }
}
