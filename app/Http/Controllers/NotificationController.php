<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$notification->est_lu) {
            $notification->update([
                'est_lu' => true,
                'date_lecture' => now(),
            ]);
        }

        return view('notifications.show', compact('notification'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update([
            'est_lu' => true,
            'date_lecture' => now(),
        ]);

        return redirect()->back()->with('success', 'Notification marquée comme lue');
    }

    public function markAllAsRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('est_lu', false)
            ->update([
                'est_lu' => true,
                'date_lecture' => now(),
            ]);

        return redirect()->back()->with('success', 'Toutes les notifications marquées comme lues');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notification supprimée');
    }
}
