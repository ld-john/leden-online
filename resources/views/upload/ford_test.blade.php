<table class="table">
    <tr>
        <th>Orbit Number</th>
        <th>Prefix</th>
        <th>Location</th>
        <th>Status</th>
        <th>Chassis Number</th>
        <th>Planned Build Date</th>
    </tr>
    @foreach($ford_test as $data)
        <tr>
            <td>
                {{ $data['orbit'] }}
            </td>
            <td>
                {{ $data['prefix'] }}
            </td>
            <td>
                {{ $data['location'] }}
            </td>
            <td>
                {{ $data['status'] }}
            </td>
            <td>
                {{ $data['chassis'] }}
            </td>
            <td>
                {{ $data['build_date'] }}
            </td>
        </tr>
    @endforeach
</table>
