<div>
     <div class="d-flex justify-content-between">
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
     </div>
     <table class="table table-bordered">
          <thead>
          <tr class="blue-background text-white">
               <th>Reservation #</th>
               <th>Customer</th>
               <th>Broker</th>
               <th>Vehicle</th>
               <th>Status</th>
               <th>Expires</th>
               <th>Actions</th>
          </tr>
          <tr class="bg-light">
               <th class="p-1"></th>
               <th class="p-1"></th>
               <th class="p-1"></th>
               <th class="p-1"></th>
               <th class="p-1"></th>
               <th class="p-1"></th>
               <th class="p-1"></th>
          </tr>
          </thead>
          <tbody>
          @forelse($reservations as $reservation)
               <tr class="@if($reservation->status === 'ordered') bg-success text-white @elseif($reservation->status === 'lapsed') bg-warning @endif ">
                    <td>
                         {{ $reservation->id }}
                    </td>
                    <td>
                         {{ $reservation->customer->firstname }} {{ $reservation->customer->lastname }}
                    </td>
                    <td>
                         {{ $reservation->company->company_name }}
                    </td>
                    <td>
                         {{ $reservation->vehicle->manufacturer->name }} {{ $reservation->vehicle->model }} @if($reservation->vehicle->orbit_number) (Orbit Number: {{ $reservation->vehicle->orbit_number }}) @endif
                         <a href="{{ route('vehicle.show', $reservation->vehicle->id) }}" class="btn btn-primary ml-4"> View</a>
                    </td>
                    <td>
                         {{ ucwords(str_replace("_", " ", $reservation->status)) }}
                    </td>
                    <td>
                         {{ \Carbon\Carbon::parse($reservation->expiry_date)->format('d/m/Y') }}
                         @if ($reservation->status === 'extended' && $reservation->leden_user)
                              <br> Extended by: {{ $reservation->leden_user->firstname }} {{ $reservation->leden_user->lastname }}
                         @endif
                    </td>
                    <td>
                         <div class="btn-group">
                              @can('admin')
                                   @if( $reservation->status === 'active' )
                                        <a href="{{route('reservation.extend', $reservation->id)}}" data-toggle="tooltip" title="Extend Reservation" class="btn btn-primary"><i class="fa-solid fa-stopwatch"></i></a>
                                   @endif
                                   @if( $reservation->status !== 'ordered' && $reservation->status !== 'lapsed' )
                                        <a data-toggle="tooltip" title="Place Order" class="btn btn-warning" wire:click="placeOrder({{ $reservation->id }})"><i class="fa-solid fa-square-plus"></i></a>
                                   @endif
                                   @if( $reservation->status === 'ordered' )
                                        <a href="{{ route('order.show', $reservation->vehicle->order->id) }}" data-toggle="tooltip" title="View Order" class="btn btn-warning"><i class="fa-solid fa-eye"></i></a>
                                   @endif
                              @endcan
                              <a wire:click="deleteReservation({{ $reservation->id }})" data-toggle="tooltip" title="Delete Order" class="btn-danger btn"><i class="fa-solid fa-trash-can"></i></a>
                         </div>

                    </td>
               </tr>
          @empty
          @endforelse
          </tbody>
     </table>
</div>
