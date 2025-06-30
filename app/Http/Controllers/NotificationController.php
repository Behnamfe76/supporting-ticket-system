<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read by reply_id for the authenticated user.
     */
    public function markAsReadByReplyId(Request $request)
    {
        $user = $request->user();
        $replyId = $request->input('reply_id');
        Reply::findOrFail($replyId)->markAsRead();
        $notification = $user->unreadNotifications()
            ->where('data->reply_id', $replyId)
            ->first();
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'not_found'], 404);
    }
}
