<?php

namespace Smartville\Http\Tenant\Controllers\Rent;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\App\Settings\TenantRentSettings;

class RentSettingsResetController extends Controller
{
    /**
     * Reset tenant's rent settings to default.
     *
     * @param Request $request
     * @param TenantRentSettings $settings
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, TenantRentSettings $settings)
    {
        $settings->forget($request->tenant()->uuid);

        return response()->json(null, 204);
    }
}
