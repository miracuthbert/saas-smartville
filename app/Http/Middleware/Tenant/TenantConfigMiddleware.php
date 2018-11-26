<?php

namespace Smartville\Http\Middleware\Tenant;

use Closure;

class TenantConfigMiddleware
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
        $tenant = $request->tenant();

        config()->set('app.name', $tenant->name);
        config()->set('app.short_name', $tenant->short_name);

        return $next($request);
    }
}
