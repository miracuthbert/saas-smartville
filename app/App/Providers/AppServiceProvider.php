<?php

namespace Smartville\App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Smartville\Domain\Amenities\Models\Amenity;
use Smartville\Domain\Categories\Models\Category;
use Smartville\Domain\Categories\Observers\CategoryObserver;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Properties\Models\Property;
use Smartville\Domain\Users\Models\Role;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Users\Observers\RoleObserver;
use Smartville\Domain\Utilities\Models\Utility;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // model observers
        Category::observe(CategoryObserver::class);
//        Tag::observe(TagObserver::class);
        Role::observe(RoleObserver::class);

        Blueprint::macro('hashid', function () {
            return $this->string('hash_id')->unique()->nullable()->index();
        });

        Relation::morphMap([
            'users' => User::class,
            'companies' => Company::class,
            'properties' => Property::class,
            'amenities' => Amenity::class,
            'utilities' => Utility::class,
        ]);

        Paginator::defaultView('vendor.pagination.bootstrap-4');

        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-4');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
