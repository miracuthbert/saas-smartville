<?php

namespace Smartville\Http\Tenant\Controllers\Utility;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\App\Settings\TenantUtilitySettings;

class UtilitySettingsController extends Controller
{
    /**
     * @var TenantUtilitySettings
     */
    public $settings;

    /**
     * UtilitySettingsController constructor.
     * @param TenantUtilitySettings $settings
     */
    public function __construct(TenantUtilitySettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $settings = $this->settings->get($request->tenant()->uuid, TenantUtilitySettings::$defaults);

        return response()->json([
            'data' => $settings,
            'meta' => [
                'default' => !$this->settings->has($request->tenant()->uuid),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->settings->put($request->tenant()->uuid, $request->all());

        return response()->json([
            'data' => $this->settings->get($request->tenant()->uuid)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $key)
    {
        $this->settings->prepend($request->tenant()->uuid, [
            $key => $request->value,
        ]);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $key
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $key)
    {
        $this->settings->forget($request->tenant()->uuid[$key]);

        return response()->json(null, 204);
    }
}
