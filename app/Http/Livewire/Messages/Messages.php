<?php

namespace App\Http\Livewire\Messages;

use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Messages extends Component
{
    public array $contacts;
    public User $activeContact;
    private $messages = [];
    public $messageContent;
    protected $listeners = ['sendActiveContact'];

    public function mount()
    {
        if (Auth::user()->role === 'admin') {
            $this->contacts = User::whereNot('id', Auth::user()->id)
                ->get()
                ->toArray();
        } else {
            $this->contacts = User::where('company_id', 7)
                ->orWhere('company_id', Auth::user()->company_id)
                ->whereNot('id', Auth::user()->id)
                ->get()
                ->toArray();
        }
        $this->activeContact = User::where(
            'id',
            $this->contacts['0']['id'],
        )->first();
        $this->fetchMessages();
    }

    public function sendActiveContact($contact)
    {
        $this->activeContact = User::where('id', $contact)->first();
        $this->fetchMessages();
    }

    public function fetchMessages()
    {
        $this->dispatchBrowserEvent('messages-fetched');
        $this->messages = Message::where(function ($query) {
            $query
                ->where('sender_id', Auth::user()->id)
                ->where('recipient_id', $this->activeContact->id);
        })
            ->orWhere(function ($query) {
                $query
                    ->where('sender_id', $this->activeContact->id)
                    ->where('recipient_id', Auth::user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($this->messages as $message) {
            if (
                !$message->recipient_read_at &&
                $message->recipient_id === Auth::user()->id
            ) {
                $message->update([
                    'recipient_read_at' => now(),
                ]);
            }
        }
    }

    public function sendMessage()
    {
        $message = new Message();
        $message->message = $this->messageContent;
        $message->sender_id = Auth::user()->id;
        $message->recipient_id = $this->activeContact->id;
        $message->save();
        $this->messageContent = '';
        $this->fetchMessages();
    }

    public function render()
    {
        return view('livewire.messages.messages');
    }
}
