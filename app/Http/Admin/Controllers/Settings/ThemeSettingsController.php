<?php

namespace Smartville\Http\Admin\Controllers\Settings;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\App\View\ThemeSettings;
use Spatie\Valuestore\Valuestore;

class ThemeSettingsController extends Controller
{
    /**
     * Instance of theme settings.
     *
     * @var $settings
     */
    public $settings;

    /**
     * ThemeSettingsController constructor.
     * @param ThemeSettings $settings
     */
    public function __construct(ThemeSettings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.settings.theme.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'data' => $this->settings->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->settings->put($request->name, $request->value);

        return response()->json([
            'data' => $this->settings->all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $key
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $key)
    {
        if (!$this->settings->has($key)) {
            return response()->json([
                'message' => 'Key does not exist'
            ], 404);
        }

        $this->settings->put($key, $request->value);

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $key
     * @return \Illuminate\Http\Response
     */
    public function destroy($key)
    {
        $this->settings->forget($key);

        return response()->json(null, 204);
    }
}
