@php use Carbon\Carbon; @endphp
<div>
    <div class="d-flex justify-content-between">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select name="" id="" wire:model.live="paginate" class="form-control mx-2">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            Offers
        </div>
        <div>
            <a class="btn btn-primary" href="{{ route('offers.create') }}">New Offer</a>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th></th>
            <th>Offer Name</th>
            <th>Offer Amount</th>
            <th>Make</th>
            <th>Model</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th>
        </tr>
        <tr class="bg-light">
            <th></th>
            <th class="p-1">
                <input wire:model.blur="searchOfferName" type="text" class="form-control"
                       placeholder="Search Offer Name">
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($offers as $offer)
            <tr>
                <td>{{ $offer->id }}</td>
                <td>{{ $offer->name }}</td>
                <td>£{{ number_format($offer->value, 2) }}</td>
                <td> {{ $offer->manufacturer?->name ?? '-' }} </td>
                <td> {{ $offer->applicableModel?->name ?? '-'}}</td>
                <td>{{ $offer->start_date ? Carbon::parse($offer->start_date)->format('d/m/Y') : '-' }}</td>
                <td>{{ $offer->end_date ? Carbon::parse($offer->end_date)->format('d/m/Y') : '-' }}</td>
                <td>
                    <a
                            href="{{route('offers.edit', $offer->id)}}"
                            class="btn btn-warning"
                            data-toggle="tooltip"
                            title="Edit Tactical Offer Details"
                    >
                        <i class="fas fa-edit"></i>
                    </a>
                    <a
                            data-toggle="tooltip"
                            title="Delete Tactical Offer"
                            href="{{ route('offers.delete', $offer->id) }}"
                            class="btn btn-danger"
                    >
                        <i class="fas fa-trash text-white"></i>
                    </a></td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No offers available at this time</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
