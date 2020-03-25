<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private $notifications;

    public function __construct()
    {
        $this->notifications = auth()->user()->notifications();
    }

    public function notifications()
    {
        return new NotificationCollection($this->notifications->paginate(10));
    }

    public function markAsRead(Request $request)
    {
        return $this->callInTransaction(function () use ($request) {
            $request->validate([
                'notifications' => 'required|array',
            ]);

            $this->notifications->whereIn('id', $request->notifications)->update(['read_at' => now()]);
        });
    }

    public function markAllAsRead()
    {
        return $this->callInTransaction(function () {
            auth()->user()->unreadNotifications()->update(['read_at' => now()]);
        });
    }

    public function deleteAll()
    {
        return $this->callInTransaction(function () {
            $this->notifications->delete();
        });
    }

    protected function callInTransaction(callable $callback)
    {
        try {
            DB::transaction(function () use ($callback) {
                call_user_func($callback);
            });
            return ['status' => true];
        } catch (\Exception $e) {
            return $e->getMessage();
            return ['status' => false];
        }
    }
}
