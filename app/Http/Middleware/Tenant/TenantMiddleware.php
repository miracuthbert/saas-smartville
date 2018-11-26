<?php

namespace Smartville\Http\Middleware\Tenant;

use Closure;
use Smartville\App\Tenant\Manager;
use Smartville\Domain\Company\Models\Company;
use Smartville\Domain\Tenant\Events\TenantIdentified;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // alternative way to resolve tenant based on last login access
        // uncomment lines below and remove the used ones
        // remember: change the routes in your views to reflect the new ones
        // new switch route name: tenant.switch params: {company}
        // new dashboard route name: tenant.dashboard params: none

        $uuid = null;

        if (auth()->check()) {
            $uuid = optional(auth()->user()->lastAccessedCompany)->uuid;
        }

        $uuid = session()->get('tenant') ?: $uuid;

        if ($request->company) {
            $uuid = $request->company;
        }

        $tenant = $this->resolveTenant($uuid);

        if (!auth()->user()->companies->contains('id', optional($tenant)->id)) {
            return redirect('/account/dashboard');
        }

        event(new TenantIdentified($tenant));

        return $next($request);
    }

    /**
     * Find passed tenant (in this case a company) by id.
     *
     * @param $uuid
     * @return mixed
     */
    protected function resolveTenant($uuid)
    {
        return Company::where('uuid', $uuid)->orWhere('id', $uuid)->first();
    }
}
