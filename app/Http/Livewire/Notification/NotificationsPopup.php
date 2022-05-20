<?php

namespace App\Http\Livewire\Notification;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationsPopup extends Component
{
    public $user;
    public bool $show = false;

    public function mount() {
        $this->user = Auth::user();
    }

    public function toggleMenu()
    {
        $this->show = !$this->show;
    }

    public function render()
    {
        $unread = $this->user->unreadNotifications()->get();
        return view('livewire.notification.notifications-popup', ['unread' => $unread]);
    }
}
