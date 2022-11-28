<div>
     <div class="d-flex justify-content-between align-items-center">
          <div class="w-25 p-3 d-flex align-items-center">
               Show
               <select wire:model="paginate" name="" id="" class="form-control mx-2">
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>
               </select>
               entries
          </div>
          <div>
               <a class="btn btn-success" href="{{ route('company.add') }}"><i class="fa-solid fa-users me-4"></i> Add New Company</a>
          </div>
     </div>
     <table class="table table-bordered">
          <thead>
          <tr class="blue-background text-white">
               <th>Leden ID</th>
               <th>Company Name</th>
               <th>Address</th>
               <th>Email</th>
               <th>Phone</th>
               <th>Type</th>
               <th>Users</th>
               <th>Actions</th>
          </tr>
          <tr class="bg-light">
               <th></th>
               <th class="p-1">
                    <input wire:model.debounce:500ms="searchName" type="text" class="form-control" placeholder="Search Name">
               </th>
               <th class="p-1">
                    <input wire:model.debounce:500ms="searchAddress" type="text" class="form-control" placeholder="Search Address">
               </th>
               <th class="p-1">
                    <input wire:model.debounce:500ms="searchEmail" type="text" class="form-control" placeholder="Search Email">
               </th>
               <th class="p-1">
                    <input wire:model.debounce:500ms="searchPhone" type="text" class="form-control" placeholder="Search Phone">
               </th>
               <th class="p-1">
                    <select wire:model.debounce:500ms="searchType" name="type" id="type" class="form-control" >
                         <option value="">Search by Type</option>
                         <option value="broker">Broker</option>
                         <option value="dealer">Dealer</option>
                         <option value="registration">Registration</option>
                         <option value="invoice">Invoice</option>
                    </select>
               </th>
               <th></th>
               <th></th>
          </tr>
          </thead>
          <tbody>
          @forelse($companies as $company)
               <tr>
                    <td>{{ $company->id }}</td>
                    <td>{{ $company->company_name }}</td>
                    <td>{{ $company->company_address1 }} <br> {{ $company->company_address2 }} <br> {{ $company->company_city }}
                         <br> {{ $company->company_county }} <br> {{ $company->company_country }} <br> {{ $company->company_postcode }}</td>
                    <td>{{ $company->company_email }}</td>
                    <td>{{ $company->company_phone }}</td>
                    <td>{{ ucfirst($company->company_type) }}</td>
                    <td>
                         <ul>
                              @forelse($company->users as $user)
                                   <li>{{$user->firstname}} {{$user->lastname}}</li>
                              @empty
                                   <li>No Users associated with this company</li>
                              @endforelse
                         </ul>

                    </td>
                    <td>
                         <div class="d-grid grid-cols-2 gap-2">
                         <a
                                 href="/companies/edit/{{$company->id}}"
                                 class="edit btn btn-warning"
                                 data-toggle="tooltip"
                                 title="Edit Company Profile"
                         >
                              <i class="fas fa-edit"></i>
                         </a>
                         </div>
                    </td>
               </tr>
          @empty
               <tr>
                    <td>No Results Found</td>
               </tr>
          @endforelse
          </tbody>
     </table>
     <div class="d-flex justify-content-between">
          @if(!$companies->isEmpty())
               <p>Showing {{ $companies->firstItem() }} - {{ $companies->lastItem() }} of {{$companies->total()}}</p>
          @endif
          <div>
               {{ $companies->links() }}
          </div>
     </div>
</div>
