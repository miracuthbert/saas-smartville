<?php

namespace Smartville\App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;
use Smartville\Domain\Leases\Policies\LeaseInvoicePolicy;
use Smartville\Domain\Leases\Policies\LeasePaymentPolicy;
use Smartville\Domain\Leases\Policies\LeasePolicy;
use Smartville\Domain\Users\Models\Role;
use Smartville\Domain\Users\Models\User;
use Smartville\Domain\Users\Policies\RolePolicy;
use Smartville\Domain\Users\Policies\UserPolicy;
use Smartville\Domain\Utilities\Policies\UtilityInvoicePolicy;
use Smartville\Domain\Utilities\Policies\UtilityPaymentPolicy;
use Smartville\Domain\Utilities\Policies\UtilityPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * The default gate abilities.
     *
     * @var array
     */
    protected $defaultGateAbilities = [
        'view' => 'view',
        'create' => 'create',
        'update' => 'update',
        'delete' => 'delete',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerGates();

        Passport::routes();
    }

    /**
     * Register the application's policies via gates.
     *
     * @return void
     */
    private function registerGates()
    {
        Gate::resource('lease', LeasePolicy::class, $this->addCustomAbilitiesToPolicy([
            'browse' => 'browse',
        ]));

        Gate::resource('leaseInvoice', LeaseInvoicePolicy::class, $this->addCustomAbilitiesToPolicy([
            'browse' => 'browse',
        ]));

        Gate::resource('leasePayment', LeasePaymentPolicy::class, $this->addCustomAbilitiesToPolicy([
            'browse' => 'browse',
        ]));

        Gate::resource('utility', UtilityPolicy::class, $this->addCustomAbilitiesToPolicy([
            'browse' => 'browse',
        ]));
        Gate::resource('utilityInvoice', UtilityInvoicePolicy::class, $this->addCustomAbilitiesToPolicy([
            'browse' => 'browse',
        ]));
        Gate::resource('utilityPayment', UtilityPaymentPolicy::class, $this->addCustomAbilitiesToPolicy([
            'browse' => 'browse',
        ]));
    }

    /**
     * Merge custom abilities with default policy abilities.
     *
     * @param array $abilities
     * @return array
     */
    private function addCustomAbilitiesToPolicy($abilities = [])
    {
        return array_merge(
            $this->defaultGateAbilities,
            $abilities
        );
    }
}
