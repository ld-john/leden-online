<?php

namespace App\Http\Livewire\Messages;

use App\Message;
use App\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Contact extends Component
{
    public User $contact;
    public Message|null $latestMessage;
    public User $activeContact;
    public $unreadFrom;

    public function mount($user)
    {
        $this->contact = User::where('id', $user)->first();
        $this->latestMessage = Message::where(function ($query) {
            $query
                ->where(
                    'sender_id',
                    \Illuminate\Support\Facades\Auth::user()->id,
                )
                ->where('recipient_id', $this->contact->id);
        })
            ->orWhere(function ($query) {
                $query
                    ->where('sender_id', $this->contact->id)
                    ->where('recipient_id', Auth::user()->id);
            })
            ->latest()
            ->first();
        $this->unreadFrom = Message::where('sender_id', $this->contact->id)
            ->where('recipient_id', Auth::user()->id)
            ->where('recipient_read_at', '=', null)
            ->count();
    }

    public function setActiveContact()
    {
        $this->activeContact = $this->contact;
        $this->emit('sendActiveContact', $this->activeContact);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.messages.contact');
    }
}