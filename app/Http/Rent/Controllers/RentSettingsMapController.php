<?php

namespace Smartville\Http\Rent\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\App\Settings\TenantRentSettings;

class RentSettingsMapController extends Controller
{
    /**
     * Get rent settings map.
     *
     * @return mixed
     */
    public function __invoke()
    {
        return response()->json([
            'data' => TenantRentSettings::map(),
            'meta' => [
                'defaults' => TenantRentSettings::$defaults,
            ],
        ]);
    }
}
