<div>
     <div class="d-flex justify-content-between">
          <div class="w-25 p-3 d-flex align-items-center">
               Show
               <select wire:model.live="paginate" name="" id="" class="form-control mx-2">
                    <option value='10'>10</option>
                    <option value='25'>25</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>

               </select>
               Customers
          </div>
          @if(count($checked) < 1)
               <button class="btn btn-primary" disabled wire:click="mergeSelected">Merge Selected Customers</button>
          @else
               <button class="btn btn-primary" wire:click="mergeSelected">Merge {{ count($checked) }} Selected Customers</button>
          @endif

     </div>

     <table class="table table-bordered">
          <thead>
          <tr class="blue-background text-white">
               <th></th>
               <th>Customer Name</th>
               <th>Address 1</th>
               <th>Address 2</th>
               <th>Town</th>
               <th>City</th>
               <th>County</th>
               <th>Postcode</th>
               <th>Phone Number</th>
               <th>Orders</th>
               <th>Customer Since</th>
               <th>Actions</th>
          </tr>
          <tr class="bg-light">
               <th></th>
               <th class="p-1">
                    <input wire:model.blur="searchCustomerName" type="text" class="form-control" placeholder="Search Name">
               </th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
               <th></th>
          </tr>
          </thead>
          <tbody>
          @foreach($customers as $customer)
               <tr>
                    <td><input type="checkbox" value="{{ $customer->id }}" wire:model.blur="checked"></td>
                    <td>{{ $customer->customer_name }}</td>
                    <td>{{ $customer->address_1 }}</td>
                    <td>{{ $customer->address_2 }}</td>
                    <td>{{ $customer->town }}</td>
                    <td>{{ $customer->city }}</td>
                    <td>{{ $customer->county }}</td>
                    <td>{{ $customer->postcode }}</td>
                    <td>{{ $customer->phone_number }}</td>
                    <td>{{ count($customer->orders) }}</td>
                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}</td>
                    <td lk>
                         <div>
                              <div class="btn-group">
                                   <a data-toggle="tooltip" title="Delete Customer"><livewire:customer.delete-customers :customer="$customer->id" :key="time().$customer->id" /></a>
                                   <a data-toggle="tooltip" title="Edit Customer"><livewire:customer.quick-edit-customer :customer="$customer->id" :key="time().$customer->id" /></a>
                              </div>
                         </div>
                    </td>
               </tr>
          @endforeach
          </tbody>
     </table>
     {{ $customers->links() }}
     @can('admin')
          <div class="container">
               <div class="card">
                    <div class="card-body">
                         <a class="btn btn-primary mb-4" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                              Potential Duplicates
                         </a>
                         <div class="collapse" id="collapseExample">
                              <div class="row row-cols-6">
                                   @foreach($duplicates as $duplicate)
                                        <div class="col border-1 p-3">{{ $duplicate }}</div>
                                   @endforeach
                              </div>
                         </div>
                    </div>
               </div>
          </div>
     @endcan
</div>

