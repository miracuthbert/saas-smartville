<?php

namespace Smartville\App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Smartville\Http\ViewComposers\AdminFeaturesComposer;
use Smartville\Http\ViewComposers\AdminPagesComposer;
use Smartville\Http\ViewComposers\AdminTutorialsComposer;
use Smartville\Http\ViewComposers\AmenitiesComposer;
use Smartville\Http\ViewComposers\CategoriesComposer;
use Smartville\Http\ViewComposers\Company\CurrentMonthTenantsMetricsComposer;
use Smartville\Http\ViewComposers\Company\PropertyMetricsComposer;
use Smartville\Http\ViewComposers\Company\CurrentMonthInvoicesComposer;
use Smartville\Http\ViewComposers\Company\CompanyPermissionsComposer;
use Smartville\Http\ViewComposers\Company\CompanyRolesComposer;
use Smartville\Http\ViewComposers\Company\CompanyUtilitiesComposer;
use Smartville\Http\ViewComposers\Company\RentMetricsComposer;
use Smartville\Http\ViewComposers\CountriesComposer;
use Smartville\Http\ViewComposers\CurrenciesComposer;
use Smartville\Http\ViewComposers\HomeComposer;
use Smartville\Http\ViewComposers\LeaseInvoiceFiltersComposer;
use Smartville\Http\ViewComposers\PagesComposer;
use Smartville\Http\ViewComposers\PageTemplatesComposer;
use Smartville\Http\ViewComposers\PermissionsComposer;
use Smartville\Http\ViewComposers\PlansComposer;
use Smartville\Http\ViewComposers\PropertyFiltersComposer;
use Smartville\Http\ViewComposers\RolesComposer;
use Smartville\Http\ViewComposers\ThemeLayoutsComposer;
use Smartville\Http\ViewComposers\TimezonesComposer;
use Smartville\Http\ViewComposers\UserCompaniesComposer;
use Smartville\Http\ViewComposers\UserFiltersComposer;
use Smartville\Http\ViewComposers\UtilitiesComposer;
use Smartville\Http\ViewComposers\UtilityInvoiceFiltersComposer;
use Smartville\Http\ViewComposers\UtilityMetricsComposer;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // home
        View::composer([
            'home.index'
        ], HomeComposer::class);

        // pages
        View::composer([
            'components._pages_dropdown'
        ], PagesComposer::class);

        // plans
        View::composer([
            'subscriptions.index'
        ], PlansComposer::class);

        // countries
        View::composer([
            'account.twofactor.index',
            'layouts.partials.forms._countries'
        ], CountriesComposer::class);

        // timezones
        View::composer([
            'layouts.partials.forms._timezones'
        ], TimezonesComposer::class);

        // categories
        View::composer([
            'tenant.properties.partials.forms._categories'
        ], CategoriesComposer::class);

        // currencies
        View::composer([
            'layouts.partials.forms._currencies'
        ], CurrenciesComposer::class);

        // amenities
        View::composer([
            'tenant.properties.partials.forms._amenities'
        ], AmenitiesComposer::class);

        // utilities
        View::composer([
            'tenant.utilities.create',
            'tenant.utilities.edit',
        ], UtilitiesComposer::class);

        // company utilities
        View::composer([
            'tenant.properties.partials.forms._utilities',
            'tenant.utilities.partials.form._utilities',
        ], CompanyUtilitiesComposer::class);

        // properties filters mappings
        View::composer([
            'tenant.properties.partials._filters'
        ], PropertyFiltersComposer::class);

        // lease invoices mappings
        View::composer([
            'tenant.rent.partials._filters'
        ], LeaseInvoiceFiltersComposer::class);

        // utility invoices mappings
        View::composer([
            'tenant.utilities.invoices.partials._filters',
        ], UtilityInvoiceFiltersComposer::class);

        // company permissions
        View::composer([
            'tenant.account.team.partials.form._roles'
        ], CompanyRolesComposer::class);

        // company permissions
        View::composer([
            'tenant.roles.partials.form._permissions'
        ], CompanyPermissionsComposer::class);

        // property metrics
        View::composer([
            'tenant.dashboard.index'
        ], PropertyMetricsComposer::class);

        // tenancy metrics
        View::composer([
            'tenant.dashboard.index'
        ], CurrentMonthTenantsMetricsComposer::class);

        // rent metrics
        View::composer([
            'tenant.dashboard.index'
        ], RentMetricsComposer::class);

        // utility metrics
        View::composer([
            'tenant.dashboard.index'
        ], UtilityMetricsComposer::class);

        // company dashboard current month invoices
        View::composer([
            'tenant.dashboard.index'
        ], CurrentMonthInvoicesComposer::class);

        // user companies
        View::composer([
            'layouts.partials._navigation'
        ], UserCompaniesComposer::class);

        // user filters mappings
        View::composer([
            'admin.users.partials._filters'
        ], UserFiltersComposer::class);

        // features list
        View::composer([
            'admin.features.create',
            'admin.features.edit'
        ], AdminFeaturesComposer::class);

        // page templates
        View::composer([
            'admin.pages.create',
            'admin.pages.edit'
        ], PageTemplatesComposer::class);

        // page layouts
        View::composer([
            'admin.pages.create',
            'admin.pages.edit'
        ], ThemeLayoutsComposer::class);

        // admin tutorials list
        View::composer([
            'admin.tutorials.create',
            'admin.tutorials.edit'
        ], AdminTutorialsComposer::class);

        // admin pages list
        View::composer([
            'admin.pages.create',
            'admin.pages.edit'
        ], AdminPagesComposer::class);

        // roles list
        View::composer([
            'admin.users.roles.partials.forms._roles',
            'admin.users.user.roles.partials.forms._roles'
        ], RolesComposer::class);

        // permissions list
        View::composer([
            'admin.users.roles.partials.forms._permissions',
        ], PermissionsComposer::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserCompaniesComposer::class);
    }
}
