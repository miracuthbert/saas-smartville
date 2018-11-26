<?php

namespace Smartville\App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Pages\Models\Page;
use Smartville\Domain\Users\Models\ConfirmationToken;
use Smartville\Http\Page\PageController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Smartville\Http';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::model('confirmation_token', ConfirmationToken::class);
        Route::model('company', Company::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapPageRoutes();

        $this->mapTenantRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web', 'bindings')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "tenant" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapTenantRoutes()
    {
        Route::middleware('web', 'auth', 'tenant')
            ->namespace("{$this->namespace}\Tenant\Controllers")
            ->group(base_path('routes/tenant.php'));
    }

    /**
     * Define the "cms pages" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapPageRoutes()
    {
        try {
            Page::with('ancestors')->live()->get()->map(function ($page) {

                // generate uri based on page ancestor slugs or page's slug
                // $page->ancestors->count() ? implode('/', $page->ancestors->pluck('slug')->toArray())
                $uri = $page->uri;

                Route::get($uri, function () use ($page) {
                    $controller = PageController::class . '@show';

                    return $this->app->call("{$controller}", [
                        'page' => $page,
                        'parameters' => $this->getCurrentRoute()->parameters()
                    ]);
                })->name($page->name)->middleware('web', 'bindings');
            });
        } catch (\Exception $e) {
            Log::debug($e->getMessage(), $e->getTrace());
        }
    }
}
