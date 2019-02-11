<?php

namespace Smartville\Http\Tenant\Controllers\Notification;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;
use Smartville\Domain\Notification\Resources\NotificationResource;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $company = $request->tenant();

        $notifications = $company->notifications()->paginate(3);

        return NotificationResource::collection($notifications)->additional([
            'meta' => [
                'unread' => $company->unreadNotifications()->count(),
            ]
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
        $request->tenant()->unreadNotifications->markAsRead();

        return response()->json(null, 204);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response|NotificationResource
     */
    public function update(Request $request, $id)
    {
        $notification = $request->tenant()->notifications()->where('id', $id)->first();
        $notification->update([
            'read_at' => now()
        ]);

        return new NotificationResource($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->tenant()->notifications()->where('id', $id)->delete();

        return response()->json(null, 204);
    }
}
