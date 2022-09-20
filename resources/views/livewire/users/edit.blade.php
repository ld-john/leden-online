<div>
    @if($type === 'profile')
        <div class="card shadow mb-4">
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Profile Information</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="firstname">First name</label>
                    <div class="col-md-4">
                        <input type="text" wire:model="first_name" name="firstname" id="firstname" required class="form-control" autocomplete="off"/>
                        @error('first_name')
                        <div class="alert alert-danger mt-2">{{ $errors->first('first_name')}}</div>
                        @enderror
                    </div>
                    <label class="col-md-2 col-form-label" for="lastname">Last name</label>
                    <div class="col-md-4">
                        <input type="text" wire:model="last_name" name="lastname" id="lastname" required class="form-control" autocomplete="off"/>
                        @error('last_name')
                        <div class="alert alert-danger mt-2">{{ $errors->first('last_name')}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="email">Email</label>
                    <div class="col-md-4">
                        <input type="text" wire:model="email" name="email" disabled id="email" required class="form-control" autocomplete="off" />
                    </div>
                    <label class="col-md-2 col-form-label" for="phone">Phone</label>
                    <div class="col-md-4">
                        <input type="tel" wire:model="phone" name="phone" id="phone" class="form-control" autocomplete="off" placeholder="e.g. 07123 456789" />
                        @error('phone')
                        <div class="alert alert-danger mt-2">{{ $errors->first('phone')}}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="avatar" class="col-md-2 col-form-label">Avatar</label>
                    <div class="col-md-4">
                        <input wire:model="avatar" type="file" class="form-control">
                        @error('avatar')
                        <div class="alert alert-danger mt-2">{{ $errors->first('avatar')}}</div>
                        @enderror
                    </div>
                        @if(is_string($avatar))
                            <div class="col-md-2">
                                Current
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset($avatar) }}" alt="">
                            </div>
                        @elseif(is_object($avatar))
                            <div class="col-md-2">
                                Preview
                            </div>
                            <div class="col-md-4">
                                <img src="{{ $avatar->temporaryUrl() }}" alt="">
                            </div>
                        @endif
                </div>
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Change Password</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="password">New Password</label>
                    <div class="col-md-4">
                        <input wire:model="password" type="password" name="password" id="password" class="form-control" autocomplete="off"/>
                        @error('password')
                        <div class="alert alert-danger mt-2">{{ $errors->first('password')}}</div>
                        @enderror
                    </div>
                    <label class="col-md-2 col-form-label" for="password_confirmation">Confirm New Password</label>
                    <div class="col-md-4">
                        <input wire:model="password_confirm" type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="off"/>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button wire:click="profileSave()" class="btn btn-primary" type="submit">Update Profile</button>
            </div>
        </div>

    @elseif($type === 'edit')
        <!-- Card Header -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">User Information</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="firstname">Firstname</label>
                <div class="col-md-4">
                    <input wire:model="first_name" type="text" name="firstname" id="firstname" required class="form-control" autocomplete="off"/>
                </div>
                <label class="col-md-2 col-form-label" for="lastname">Lastname</label>
                <div class="col-md-4">
                    <input wire:model="last_name" type="text" name="lastname" id="lastname" required class="form-control" autocomplete="off" >
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="email">Email</label>
                <div class="col-md-4">
                    <input wire:model="email" type="text" name="email" id="email" required class="form-control" autocomplete="off"/>
                    @error('email')
                    <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <label class="col-md-2 col-form-label" for="phone">Phone</label>
                <div class="col-md-4">
                    <input wire:model="phone" type="text" name="phone" id="phone" class="form-control" autocomplete="off" placeholder="e.g. 07123 456789"/>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label" for="company_id">Company</label>
                <div class="col-md-4">
                    <select wire:model="company_id" name="company_id" id="company_id" class="form-control" required autocomplete="off">
                        <option value="">Please select</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" @if ($user->company_id == $company->id) selected @endif>{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <label class="col-md-2 col-form-label" for="role">User Role</label>
                <div class="col-md-4">
                    <select wire:model="role" name="role" id="role" class="form-control" required autocomplete="off">
                        <option value="">Please select</option>
                        <option value="admin">Admin</option>
                        <option value="dealer">Dealer</option>
                        <option value="broker">Broker</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="avatar" class="col-md-2 col-form-label">Avatar</label>
                <div class="col-md-4">
                    <input wire:model="avatar" type="file" class="form-control">
                    @error('avatar')
                    <div class="alert alert-danger mt-2">{{ $errors->first('avatar')}}</div>
                    @enderror
                </div>
                @if(is_string($avatar))
                    <div class="col-md-2">
                        Current
                    </div>
                    <div class="col-md-4">
                        <img src="{{ asset($avatar) }}" alt="">
                    </div>
                @elseif(is_object($avatar))
                    <div class="col-md-2">
                        Preview
                    </div>
                    <div class="col-md-4">
                        <img src="{{ $avatar->temporaryUrl() }}" alt="">
                    </div>
                @endif
            </div>
        </div>
        <!-- Card Footer -->
        <div class="card-footer text-right">
            <a href="{{ route('user_manager') }}" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" wire:click="editSave()">Update User</button>
        </div>
    @endif

</div>
