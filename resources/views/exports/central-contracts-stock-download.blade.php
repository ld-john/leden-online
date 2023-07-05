<table>
    <thead>
    <tr>
        <th>Order Ref</th>
    </tr>
    </thead>
    <tbody>
    @foreach($vehicles as $vehicle)
        <tr>
            <td>{{ $vehicle->id }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

