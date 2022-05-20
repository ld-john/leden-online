@extends('layouts.main', [
    'title' => 'Fit Options - Select Fields',
    'activePage' => 'fit-options-upload'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Fit Options Upload - Select Fields</h1>
                <div class="card shadow mb-4">
                    <form action="{{route('import_process')}}" method="POST">
                        @csrf
                        <input type="hidden" name="csv_data_field_id" value="{{ $csv_data_file->id }}">
                        <table class="table">
                            @if(isset($headings))
                                <thead>
                                <tr>
                                    @foreach($headings[0][0] as $csv_header_field)
                                        <th>
                                            <span>{{$csv_header_field}}</span>
                                        </th>
                                    @endforeach
                                </tr>
                                </thead>
                            @endif
                                <tbody class="bg-white divide-y divide-gray-200 divide-solid">
                                @foreach($csv_data as $row)
                                        @foreach($row as $value)
                                            <tr>
                                            @foreach($value as $field)
                                                <td> {{ $field }}</td>
                                            @endforeach
                                            </tr>
                                        @endforeach
                                @endforeach
                                <tr>
                                    @foreach ($csv_data[0][0] as $key => $value)
                                        <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">
                                            <select name="fields[{{ $key }}]">
                                                @foreach (config('app.db_fields') as $db_field)
                                                    <option value="{{ (\Request::has('header')) ? $db_field : $loop->index }}"
                                                            @if ($key === $db_field) selected @endif>{{ $db_field }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    @endforeach
                                </tr>
                                </tbody>
                        </table>
                        <input type="submit" value="Upload" class="mt-4 btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
