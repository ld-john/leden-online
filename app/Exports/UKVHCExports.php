<?php

namespace App\Exports;

use App\Vehicle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UKVHCExports implements FromView
{
    public function view(): View
    {
        $vehicles = Vehicle::where('vehicle_status', 11)->with('manufacturer:id,name')->get();

        return view('exports.vehicles', [
            'vehicles' => $vehicles
        ]);
    }
}
