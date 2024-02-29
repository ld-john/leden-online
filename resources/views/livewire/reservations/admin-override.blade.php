<div>
    @if($reserved)
        <div class="alert alert-danger">
            This vehicle is already reserved for {{ $reserved->customer->fullName }} from {{ $reserved->company->company_name }} until {{ \Carbon\Carbon::parse($vehicle->reservation->expiry_date)->format('d/m/Y') }}
            <br><br>
            Continuing will cancel this reservation.
        </div>

    @endif
    <table class="table table-bordered mt-4">
        <thead>
        <tr class="blue-background text-white">
            <th style="width: 45px"></th>
            <th>Broker Name</th>
            <th>Broker Company Name</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1"></th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchName" type="text" class="form-control" placeholder="Search Name">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchCompany" class="form-control" placeholder="Search Company" type="text">
            </th>
        </tr>
        </thead>
        <tbody>
    @forelse ( $brokers as $broker )
        <tr>
            <td><input type="radio" value="{{$broker->id}}" wire:model.live="broker_id"></td>
            <td>{{ $broker->fullName }}</td>
            @if ($broker->company)
            <td>{{ $broker->company->company_name }}</td>
            @else
            <td></td>
            @endif
        </tr>
    @empty
        <tr>
            <td colspan="3">
                No Results
            </td>
        </tr>
    @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        @if(!$brokers->isEmpty())
            <p>Showing {{ $brokers->firstItem() }} - {{ $brokers->lastItem() }} of {{$brokers->total()}}</p>
        @endif
        <div>
            {{ $brokers->links() }}
        </div>
    </div>

        <a wire:click="reserveVehicle()" class="btn btn-primary @if (!$broker_id) disabled @endif" >Reserve Vehicle #{{ $vehicle->id }}</a>

</div>
