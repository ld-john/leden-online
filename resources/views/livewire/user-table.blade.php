<div>
     <div class="d-flex justify-content-between align-items-center">
          <div class="w-25 p-3 d-flex align-items-center">
               Show
               <select wire:model.live="paginate" name="" id="" class="form-control mx-2">
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>
               </select>
               entries
          </div>
          <div>
               <a class="btn btn-success" href="{{ route('user.add') }}"><i class="fa-solid fa-user me-4"></i> Add New User</a>
          </div>
     </div>
     <table class="table table-bordered">
          <thead>
          <tr class="blue-background text-white">
               <th>ID</th>
               <th>First Name</th>
               <th>Last Name</th>
               <th>Email</th>
               <th>Company</th>
               <th>Role</th>
               <th>Action</th>
          </tr>
          <tr class="bg-light">
               <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchID" type="text" class="form-control" placeholder="Search ID">
               </th>
               <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchFirstName" type="text" class="form-control" placeholder="Search First Name">
               </th>
               <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchLastName" type="text" class="form-control" placeholder="Search Last Name">
               </th>
               <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchEmail" type="text" class="form-control" placeholder="Search Email">
               </th>
               <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchCompany" type="text" class="form-control" placeholder="Search Company">
               </th>
               <th class="p-1">
                    <select wire:model.live.debounce:500ms="searchRole" name="role" id="role" class="form-control" >
                         <option value="">Search by Role</option>
                         <option value="admin">Admin</option>
                         <option value="dealer">Dealer</option>
                         <option value="broker">Broker</option>
                    </select>
               </th>
               <th></th>
          </tr>
          </thead>
          <tbody>
          @forelse($users as $user)
               <tr>
                    <td>
                         {{ $user->id }}
                    </td>
                    <td>
                         {{ $user->firstname }}
                    </td>
                    <td>
                         {{ $user->lastname }}
                    </td>
                    <td>
                         {{ $user->email }}
                    </td>
                    <td>
                         @if($user->company)
                              {{ $user->company->company_name }}
                         @endif
                    </td>
                    <td>
                         {{ ucwords($user->role) }}
                    </td>
                    <td width="120px">
                         @if($user->is_deleted === null)
                              <div class="d-grid grid-cols-2 gap-2">
                                   <a href="/user-management/edit/{{$user->id}}" class="btn btn-primary" data-toggle="tooltip" title="Edit Profile"><i class="fas fa-edit"></i></a>
                                   <a href="/user-management/disable/{{$user->id}}" class="btn btn-danger" data-toggle="tooltip" title="Disable Profile"><i class="fas fa-times"></i></a>
                                   @if($user->role === 'broker')
                                        <a href="{{route('reservation.toggle', $user->id)}}"  data-toggle="tooltip" @if($user->reservation_allowed === 1) class="btn btn-warning" title="Disable Reservation" @else class="btn btn-success" title="Reactivate Reservation" @endif ><i class="fa-solid fa-car"></i></a>
                                   @endif
                              </div>

                         @else
                              <div class="d-grid grid-cols-2 gap-2">
                                   <a href="/user-management/disable/{{$user->id}}" class="btn btn-success" data-toggle="tooltip" title="Enable Profile"><i class="fas fa-check"></i></a>
                                   <a href="/user-management/delete/{{$user->id}}" class="btn btn-danger" data-toggle="tooltip" title="Delete Profile"><i class="fas fa-trash"></i></a>

                              </div>
                         @endif
                    </td>
               </tr>

          @empty
               <tr>
                    <td colspan="7">No Matching Results found</td>
               </tr>
          @endforelse
          </tbody>
     </table>
     <div class="d-flex justify-content-between">
          @if(!$users->isEmpty())
               <p>Showing {{ $users->firstItem() }} - {{ $users->lastItem() }} of {{$users->total()}}</p>
          @endif
          <div>
               {{ $users->links() }}
          </div>
     </div>
</div>
