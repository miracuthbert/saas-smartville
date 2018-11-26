<?php

namespace Smartville\App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Smartville\App\Tenant\Cache\TenantCacheManager;
use Smartville\App\Tenant\Manager;
use Smartville\App\Tenant\Observers\TenantObserver;
use Smartville\Domain\Properties\Observers\PropertyObserver;
use Smartville\Domain\Users\Models\Permission;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(Manager::class, function () {
            return new Manager();
        });

        $this->app->singleton(TenantObserver::class, function () {
            return new TenantObserver(app(Manager::class)->getTenant());
        });

        $this->app->singleton(PropertyObserver::class, function () {
            return new PropertyObserver(app(Manager::class)->getTenant());
        });

        Request::macro('tenant', function () {
           return app(Manager::class)->getTenant();
        });

        Blade::if('tenant', function() {
            return app(Manager::class)->hasTenant();
        });

        try {
            Permission::where('usable', true)->forCompany()->get()->map(function ($permission) {
                Gate::define($permission->name, function ($user) use ($permission) {
                    return $user->hasCompanyPermissionTo($permission);
                });
            });
        } catch (\Exception $e) {
            // log or do do something here
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend('cache', function () {
            return new TenantCacheManager($this->app);
        });
    }
}
