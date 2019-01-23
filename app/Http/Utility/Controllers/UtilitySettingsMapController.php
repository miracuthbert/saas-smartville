<?php

namespace Smartville\Http\Utility\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\App\Settings\TenantUtilitySettings;

class UtilitySettingsMapController extends Controller
{
    /**
     * Get rent settings map.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return response()->json([
            'data' => TenantUtilitySettings::map(),
            'meta' => [
              'defaults' => TenantUtilitySettings::$defaults,
            ],
        ]);
    }
}
