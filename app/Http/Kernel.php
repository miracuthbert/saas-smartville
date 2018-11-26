<?php

namespace Smartville\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Smartville\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Smartville\Http\Middleware\TrustProxies::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Smartville\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Smartville\Http\Middleware\VerifyCsrfToken::class,
            \Smartville\Http\Middleware\Admin\Impersonate::class,
        ],

        'tenant' => [
            \Smartville\Http\Middleware\Tenant\TenantMiddleware::class,
            \Smartville\Http\Middleware\Tenant\TenantConfigMiddleware::class,
            'bindings',
        ],

        'api' => [
            'throttle:60,1',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \Smartville\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'confirmation_token.expired' => \Smartville\Http\Middleware\ChecksExpiredConfirmationTokens::class,
        'role' => \Smartville\Http\Middleware\AbortIfHasNoRole::class,
        'permission' => \Smartville\Http\Middleware\AbortIfHasNoPermission::class,
        'auth.register' => \Smartville\Http\Middleware\AuthenticateRegister::class,
        'subscription.active' => \Smartville\Http\Middleware\Subscription\RedirectIfNotActive::class,
        'subscription.notcancelled' => \Smartville\Http\Middleware\Subscription\RedirectIfCancelled::class,
        'subscription.cancelled' => \Smartville\Http\Middleware\Subscription\RedirectIfNotCancelled::class,
        'subscription.customer' => \Smartville\Http\Middleware\Subscription\RedirectIfNotCustomer::class,
        'subscription.inactive' => \Smartville\Http\Middleware\Subscription\RedirectIfNotInactive::class,
        'subscription.team' => \Smartville\Http\Middleware\Subscription\RedirectIfNoTeamPlan::class,
        'subscription.owner' => \Smartville\Http\Middleware\Subscription\RedirectIfNotSubscriptionOwner::class,
        'property.occupied' => \Smartville\Http\Middleware\Tenant\Property\RedirectIfPropertyHasTenant::class,
    ];
}
