<?php

namespace App\Exports;

use App\Vehicle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DashboardExports implements FromView
{
    protected $vehicles;

    /**
     * @return mixed
     */
    public function __construct(object $vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function view() : View
    {
        return view('exports.vehicles', [
            'vehicles' => $this->vehicles
        ]);

    }
}
