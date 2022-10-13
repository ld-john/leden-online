@extends('layouts.main', [
    'title' => 'Dashboard',
    'activePage' => 'dashboard'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <div class="row">
            @include('dashboard.partials.boxes')
        </div>

        <!-- Content Row -->
        @if(count($updates) > 0)
            <div class="row">
                <div class="col-lg-12">
                    <div class="news-container card shadow mb-4 w-100">
                        <div class="title">News from Leden</div>
                        <ul>
                            @foreach($updates as $update)
                                <li>{{ $update->update_text }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if(count($banners) === 1)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4 w-100">
                        <div class="position-relative w-100">
                            <div class="position-absolute top-50 start-0 translate-middle-y ms-4 w-25 p-2 bg-white shadow">
                                <h3>{{ $banners[0]->header }}</h3>
                                @if($banners[0]->update_text)
                                    <p>{{ $banners[0]->update_text }}</p>
                                @endif
                            </div>
                            <img src="{{ asset($banners[0]->image) }}" alt="{{ $banners[0]->header }}" class="w-100">
                        </div>
                    </div>
                </div>
            </div>
        @elseif(count($banners) > 1)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4 w-100">
                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($banners as $banner)
                                    <div class="carousel-item @if($loop->index === 0) active @endif">
                                        <div class="position-relative w-100">
                                            <div class="position-absolute top-50 start-0 translate-middle-y ms-4 w-25 p-2 bg-white shadow">
                                                <h3>{{ $banner->header }}</h3>
                                                @if($banner->update_text)
                                                    <p>{{ $banner->update_text }}</p>
                                                @endif
                                            </div>
                                            <img src="{{ asset($banner->image) }}" alt="{{ $banner->header }}" class="w-100">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">

            <!-- Vehicle Offers -->
            {{--            <div class="col-lg-12">--}}
            {{--                <div class="card shadow mb-4">--}}
            {{--                    <!-- Card Header - Dropdown -->--}}
            {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
            {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Special Offers</h6>--}}
            {{--                    </div>--}}
            {{--                    <!-- Card Body -->--}}
            {{--                    <div class="card-body">--}}
            {{--                        <div class="table-responsive">--}}
            {{--                            <table class="table table-bordered">--}}
            {{--                                <thead>--}}
            {{--                                <tr class="blue-background text-white">--}}
            {{--                                    <th>Leden ID</th>--}}
            {{--                                    <th>Make</th>--}}
            {{--                                    <th>Model</th>--}}
            {{--                                    <th>Engine</th>--}}
            {{--                                    <th>Type</th>--}}
            {{--                                    <th>Action</th>--}}
            {{--                                </tr>--}}
            {{--                                </thead>--}}
            {{--                                <tbody>--}}
            {{--                                @foreach( $data as $row )--}}
            {{--                                    <tr>--}}
            {{--                                        <td>{{ $row->id ?? '' }}</td>--}}
            {{--                                        <td>{{ $row->manufacturer->name ?? '' }}</td>--}}
            {{--                                        <td>{{ $row->model ?? '' }}</td>--}}
            {{--                                        <td>{{ $row->engine ?? '' }}</td>--}}
            {{--                                        <td>{{ $row->type ?? '' }}</td>--}}
            {{--                                        <td>--}}
            {{--                                            <a href="{{route('vehicle.show', [$row->id])}}" class="btn btn-primary"><i class="far fa-eye"></i> View</a>--}}
            {{--                                        </td>--}}
            {{--                                    </tr>--}}
            {{--                                @endforeach--}}
            {{--                                </tbody>--}}
            {{--                            </table>--}}
            {{--                        </div>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <!-- Orders in Stock -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">In Stock Orders</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        @livewire('dashboard.broker.in-stock-orders-table')
                    </div>
                </div>
            </div>

        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Notifications -->
            @include('dashboard.partials.notifications')

            <!-- Messages -->
            <div class="col-lg-6">
                @include('dashboard.partials.messages')
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection
