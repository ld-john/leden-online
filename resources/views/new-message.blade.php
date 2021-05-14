@extends('layouts.main', [
    'title' => 'New Message',
    'activePage' => 'messages.index'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-10">
                <h1 class="h3 mb-4 text-gray-800">Send A New Message</h1>
                <form method="POST" action="{{ route('message.send') }}">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Message Details</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="subject">Subject</label>
                                <div class="col-md-10">
                                    <input type="text" name="subject" id="subject" required class="form-control" autocomplete="off" value="{{ old('subject') }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="recipient_id">Recipient</label>
                                <div class="col-md-4">
                                    <select name="recipient_id" id="recipient_id" required class="form-control" autocomplete="off">
                                        <option value="">Please Select Recipient</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <label class="col-md-2 col-form-label" for="order_id">Order ID (Optional)</label>
                                <div class="col-md-4">
                                    <select name="order_id" id="order_id" class="form-control" autocomplete="off">
                                        <option value="">Please Select Recipient</option>
                                        @foreach ($orders as $order)
                                            <option value="{{ $order->id }}">{{ $order->id }} - {{$order->vehicle->manufacturer->name}} {{$order->vehicle->model}}@if($order->vehicle->reg) - {{$order->vehicle->reg}}@endif</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="message">Message</label>
                                <div class="col-md-10">
                                    <textarea name="message" id="message" class="form-control" rows="15"></textarea>
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer text-right">
                            <a href="{{ route('messages') }}" class="btn btn-secondary">Cancel</a>
                            <button class="btn btn-primary" type="submit">Send Message</button>
                        </div>
                    </div>
            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')
    <script type="text/javascript" src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript">
        tinymce.init({
            selector : "textarea",
            plugins : ["advlist autolink lists link image charmap print preview anchor", "searchreplace visualblocks code fullscreen", "insertdatetime media table contextmenu paste jbimages"],
            toolbar : "undo redo | bold italic link | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
            menubar : false,
        });
    </script>
@endpush
