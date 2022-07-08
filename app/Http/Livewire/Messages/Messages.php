<?php

namespace App\Http\Livewire\Messages;

use App\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Messages extends Component
{

    protected $contacts;

    public function mount()
    {
        if (Auth::user()->role === 'admin')
        {
            $this->contacts = User::all();
        }
    }

    public function render()
    {
        return view('livewire.messages.messages');
    }
}
