<?php

namespace App\Http\Livewire\Users;

use App\Company;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public $type;
    public $user;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $company_id;
    public $role;
    public $password;
    public $password_confirmation;
    public $avatar;
    public $companies;

    public function mount($type, $user)
    {
        $this->type = $type;
        $this->user = $user;
        $this->first_name = $user->firstname;
        $this->last_name = $user->lastname;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->avatar = $user->avatar;
        $this->companies = Company::all();
        $this->company_id = $user->company_id;
        $this->role = $user->role;
    }
    public function render(): Factory|View|Application
    {
        return view('livewire.users.edit');
    }
    public function profileSave()
    {
        if (is_string($this->avatar)) {
            $this->avatar = null;
        }
        $validate = $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric',
            'avatar' => 'sometimes|nullable|image|max:2048',
            'password' => 'sometimes|confirmed',
        ]);
        $this->user->update([
            'firstname' => $validate['first_name'],
            'lastname' => $validate['last_name'],
            'phone' => $validate['phone'],
        ]);

        if ($validate['password']) {
            $this->user->update([
                'password' => bcrypt($validate['password']),
            ]);
        }

        if ($this->avatar) {
            $this->user->update([
                'avatar' => $this->avatar->store('images/avatar'),
            ]);
        }

        notify()->success(
            'User Profile Updated Successfully',
            'Profile Updated',
        );
        return redirect(route('profile'));
    }
    public function editSave()
    {
        if (is_string($this->avatar)) {
            $this->avatar = null;
        }
        $validate = $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'role' => 'required',
            'avatar' => 'sometimes|nullable|image|max:2048',
            'password' => 'sometimes|nullable|confirmed',
        ]);
        $this->user->update([
            'firstname' => $validate['first_name'],
            'lastname' => $validate['last_name'],
            'phone' => $validate['phone'],
            'email' => $validate['email'],
            'role' => $validate['role'],
            'company_id' => $this->company_id,
        ]);

        if ($validate['password']) {
            $this->user->update([
                'password' => bcrypt($this->password),
                'firstname' => $validate['first_name'],
            ]);
        }

        if ($this->avatar) {
            $this->user->update([
                'avatar' => $this->avatar->store('images/avatar'),
            ]);
        }
        notify()->success(
            'User Profile Updated Successfully',
            'Profile Updated',
        );
        return redirect(route('user_manager'));
    }
}
