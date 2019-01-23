<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\App\Settings\TenantUtilitySettings;

class UtilitySettingsResetController extends Controller
{
    /**
     * Reset tenant's utilities settings to default.
     *
     * @param Request $request
     * @param TenantUtilitySettings $settings
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, TenantUtilitySettings $settings)
    {
        $settings->forget($request->tenant()->uuid);

        return response()->json(null, 204);
    }
}
