<div>
    <button
            type="button"
            class="btn btn-danger delete-order"
            wire:click="toggleDeleteModal"
    >
        <i class="fas fa-trash"></i>
    </button>
    <div class="modal @if($modalShow) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Customer Record - {{ $customer->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleDeleteModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if (count($customer->orders) > 0 )
                        <p>{{ $customer->customer_name ?? 'This customer' }} has placed the following orders:</p>
                        <ul>
                            @foreach($orders as $order)
                                <li class="mb-2">
                                    <span class="d-flex justify-content-between">
                                        <span><a target="_blank" href="{{ route('order.show', $order->id) }}">{{$order->id}}</a> - {{ $order->vehicle?->manufacturer?->name }} {{ $order->vehicle?->model }}</span>
                                        <a data-toggle="tooltip" title="Delete Order"><livewire:order.delete-order :order="$order->id" :key="time().$order->id" /></a>
                                    </span>
                                </li>

                            @endforeach
                        </ul>
                        <p>Please remove these orders before deleting the customer record.</p>
                    @else
                    <p>Are you sure you want to delete the record for {{ $customer->customer_name ?? 'this customer' }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDeleteModal">Close</button>
                    @if (count($customer->orders) === 0 )
                        <button type="button" class="btn btn-danger" wire:click="deleteCustomer">Delete</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
