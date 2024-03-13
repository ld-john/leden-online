@extends('layouts.main', [
    'title' => $title,
    'activePage' => $active_page
    ])

@section('content')
    <!-- Begin Page Content -->

    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">{{$title}}</h1>
                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <h2 class="h4">Customers</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Address 1</th>
                                    <th>Address 2</th>
                                    <th>Town</th>
                                    <th>City</th>
                                    <th>County</th>
                                    <th>Postcode</th>
                                    <th>Phone Number</th>
                                    <th>Customer Since</th>
                                    <th>Moved to Recycle Bin</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->customer_name }}</td>
                                        <td>{{ $customer->address_1 }}</td>
                                        <td>{{ $customer->address_2 }}</td>
                                        <td>{{ $customer->town }}</td>
                                        <td>{{ $customer->city }}</td>
                                        <td>{{ $customer->county }}</td>
                                        <td>{{ $customer->postcode }}</td>
                                        <td>{{ $customer->phone_number }}</td>
                                        <td>{{ Carbon\Carbon::parse($customer->created_at)->diffForHumans() }}</td>
                                        <td>{{ Carbon\Carbon::parse($customer->deleted_at)->diffForHumans() }}</td>
                                        <td>
                                            <div class="d-grid grid-cols-2 gap-2">
                                                <a href="{{ route('customer.restore', $customer->id) }}" class="btn btn-success" data-toggle="tooltip" title="Restore">
                                                    <i class="fa-solid fa-trash-arrow-up"></i>
                                                </a>
                                                <a
                                                        href="{{ route('customer.force-delete', $customer->id) }}"
                                                        class="btn btn-danger"
                                                        data-toggle="tooltip"
                                                        title="PERMANENTLY DELETE"
                                                >
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                @endforeach

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $customers->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
