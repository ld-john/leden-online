<?php

namespace App\Exports;

use App\Vehicle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeliveryBookedExport implements FromView
{
    public function view(): View
    {
        $vehicles = Vehicle::where('vehicle_status', 6)->with('manufacturer:id,name')->get();

        return view('exports.vehicles', [
            'vehicles' => $vehicles
        ]);
    }
}
