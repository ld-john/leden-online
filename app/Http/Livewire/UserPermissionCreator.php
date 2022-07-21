<?php

namespace App\Http\Livewire;

use App\Permission;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Str;

class UserPermissionCreator extends Component
{
    public $permission_name;
    public $user;
    public $assigned_permissions = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->assigned_permissions = $user->canPerform->pluck('id');
    }
    public function addNewPermission()
    {
        $permission = new Permission();
        $permission->label = $this->permission_name;
        $permission->name = Str::slug($this->permission_name);
        $permission->save();
    }
    public function assignPermissions()
    {
        $this->user->canPerform()->sync($this->assigned_permissions);
    }
    public function render(): Factory|View|Application
    {
        $permissions = Permission::all();
        return view('livewire.user-permission-creator', [
            'permissions' => $permissions,
        ]);
    }
}
