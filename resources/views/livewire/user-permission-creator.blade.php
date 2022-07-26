<div>
    @php( $user_permissions = Auth::user()->canPerform->pluck('name')->toArray())
    @if(in_array('manages-user-permissions', $user_permissions))
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Assign User Permissions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table">
                                <tr>
                                    <th></th>
                                    <th>Permission Name</th>
                                    <th>Current Users</th>
                                </tr>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td><input type="checkbox" value="{{ $permission->id }}" wire:model="assigned_permissions" @if($user->canPerform->contains($permission->id)) checked @endif></td>
                                        <td>{{ $permission->label }}</td>
                                        <td>
                                            <ul>
                                                @foreach($permission->users as $user)
                                                    <li>{{ $user->firstname }} {{ $user->lastname }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a class="btn btn-primary" wire:click="assignPermissions">Assign</a>
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if(in_array('adds-new-user-permissions', $user_permissions))
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Add New User Permissions</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" for="name">Permission Name</label>
                            <div class="col-md-8">
                                <input type="text" wire:model="permission_name" name="name" id="name" class="form-control" autocomplete="off"/>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a wire:click="addNewPermission" class="btn btn-primary">Add</a>
                    </div>
                </div>
            </div>
        </div>

</div>

@endif
