<?php

namespace App\Http\Livewire\Notification;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationPanel extends Component
{
    public function render()
    {
        $notifications = Auth::user()->notifications->take(4);
        return view('livewire.notification.notification-panel', ['notifications' => $notifications]);
    }
}
