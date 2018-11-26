<?php

namespace Smartville\Http\Notification\Controllers;

use Illuminate\Http\Request;
use Smartville\App\Controllers\Controller;

class NotificationUnreadController extends Controller
{
    /**
     * Get user's unread notifications in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function unread(Request $request)
    {
        $unreadCount = $request->user()->unreadNotifications()->count();

        return response()->json([
            'unread' => $unreadCount
        ], 200);
    }
}
