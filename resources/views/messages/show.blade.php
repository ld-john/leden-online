@extends('layouts.main', [
    'title' => $message_info->subject,
    'activePage' => 'messages'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">
        <h1 class="h3 mb-4 text-gray-800">RE: {{ $message_info->subject }}</h1>
        <!-- Content Row -->
        @foreach ($messages as $message)
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <span class="font-weight-bold">{{ $message->firstname }} {{ $message->lastname }}</span>
                                            <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($message->created_at)) }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    @if ($message->role == 'admin')
                                        <span class="badge badge-danger">Admin</span>
                                    @elseif ($message->role == 'Dealer')
                                        <span class="badge badge-info">Dealer</span>
                                    @else
                                        <span class="badge badge-secondary">Broker</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            {!! $message->message !!}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
    <!-- /.container-fluid -->

    <div class="row justify-content-center">

        <div class="col-lg-10">
            <form method="POST" action="{{ route('message.reply', $message_info->mg_id) }}">
                @csrf
                @if ($recipients->recipient_id == Auth::user()->id)
                    <input type="hidden" name="recipient_id" value="{{ $recipients->sender_id }}">
                @else
                    <input type="hidden" name="recipient_id" value="{{ $recipients->recipient_id }}">
                @endif
                <div class="card shadow mb-4">
                    <!-- Card Header -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Message Details</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Subject</label>
                            <div class="col-md-4 col-form-label">
                                <strong>{{ $message_info->subject }}</strong>
                            </div>
                            <label class="col-md-2 col-form-label">Order ID (Optional)</label>
                            <div class="col-md-4 col-form-label">
                                @if ($message_info->order_id != '')
                                    <strong>{{ $message_info->order_id }}</strong>
                                @else
                                    <em>None</em>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="company_id">Message</label>
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
