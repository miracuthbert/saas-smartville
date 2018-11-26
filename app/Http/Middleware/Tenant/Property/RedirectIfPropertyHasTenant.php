<?php

namespace Smartville\Http\Middleware\Tenant\Property;

use Closure;
use Smartville\Domain\Properties\Models\Property;

class RedirectIfPropertyHasTenant
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
        $property = $request->property;

        if ($this->checkIfOccupied($property)) {
            return back()
                ->withWarning("{$property->name} has an active tenant. Please choose another property to add tenant.");
        }

        return $next($request);
    }

    /**
     * Return whether property has an tenant.
     *
     * @param Property $property
     * @return bool
     */
    protected function checkIfOccupied(Property $property)
    {
        return $property->hasActiveTenant();
    }
}
