<?php

namespace Smartville\App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Smartville\Domain\Auth\Events\UserRequestedActivationEmail;
use Smartville\Domain\Auth\Events\UserSignedUp;
use Smartville\Domain\Auth\Listeners\CreateDefaultTeam;
use Smartville\Domain\Auth\Listeners\SendActivationEmail;
use Smartville\Domain\Company\Events\ExistingUserTeamInvitation;
use Smartville\Domain\Company\Events\UnregisteredUserTeamInvitation;
use Smartville\Domain\Company\Listeners\CompanyUserEventSubscriber;
use Smartville\Domain\Company\Listeners\SendExistingUserTeamInvitationEmail;
use Smartville\Domain\Company\Listeners\SendUnregisteredUserTeamInvitationEmail;
use Smartville\Domain\Properties\Events\ExistingUserTenantInvitation;
use Smartville\Domain\Properties\Events\Tenant\Invitations\ResendTenantInvitation;
use Smartville\Domain\Properties\Events\UnregistedUserTenantInvitation;
use Smartville\Domain\Properties\Listeners\Tenant\Invitations\SendExistingUserTenantInvitationEmail;
use Smartville\Domain\Properties\Listeners\Tenant\Invitations\SendUnregisteredUserTenantInvitationEmail;
use Smartville\Domain\Properties\Listeners\Tenant\Leases\CreateExistingUserTenantLease;
use Smartville\Domain\Properties\Listeners\Tenant\Leases\CreateUnregisteredUserTenantLease;
use Smartville\Domain\Tenant\Events\TenantIdentified;
use Smartville\Domain\Tenant\Listeners\RegisterTenant;
use Smartville\Domain\Tenant\Listeners\SetupCompanyRoles;
use Smartville\Domain\Tenant\Listeners\UseTenantFilesystem;
use Smartville\Domain\Users\Events\NewUserInvited;
use Smartville\Domain\Users\Events\UpdateUserLastLogin;
use Smartville\Domain\Users\Listeners\SendAdminNewUserInvitedNotification;
use Smartville\Domain\Users\Listeners\SendUserInvitationEmail;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            UpdateUserLastLogin::class,
        ],
        UserSignedUp::class => [
//            CreateDefaultTeam::class,
            SendActivationEmail::class,
        ],
        UserRequestedActivationEmail::class => [
            SendActivationEmail::class,
        ],
        UnregistedUserTenantInvitation::class => [
            CreateUnregisteredUserTenantLease::class,
            SendUnregisteredUserTenantInvitationEmail::class,
        ],
        ExistingUserTenantInvitation::class => [
            CreateExistingUserTenantLease::class,
            SendExistingUserTenantInvitationEmail::class,
        ],
        ResendTenantInvitation::class => [
            SendUnregisteredUserTenantInvitationEmail::class,
        ],
        ExistingUserTeamInvitation::class => [
            SendExistingUserTeamInvitationEmail::class,
        ],
        UnregisteredUserTeamInvitation::class => [
            SendUnregisteredUserTeamInvitationEmail::class,
        ],
        'Smartville\Domain\Company\Events\CompanyCreated' => [  // todo: setup listeners
            'Smartville\Domain\Company\Listeners\SendCompanyWelcomeEmail',
            'Smartville\Domain\Company\Listeners\RegisterCreatedTenant',
            'Smartville\Domain\Company\Listeners\CreateCompanyRoles',
            'Smartville\Domain\Company\Listeners\CreateCompanyAdmin',
            'Smartville\Domain\Company\Listeners\SeedCompany',
        ],
        TenantIdentified::class => [
            RegisterTenant::class,
            UseTenantFilesystem::class,
//            SetupCompanyRoles::class,
        ],
        NewUserInvited::class => [
            SendUserInvitationEmail::class,
            SendAdminNewUserInvitedNotification::class,
        ],
        'Smartville\Domain\Leases\Events\TenantVacated' => [  // todo: setup listeners
            'Smartville\Domain\Leases\Listeners\SendAdminTenantVacatedEmail',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        CompanyUserEventSubscriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
