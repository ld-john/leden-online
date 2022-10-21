<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DashboardExports implements FromView
{
    protected $vehicles;

    /**
     * @return void
     */
    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function view(): View
    {
        return view('exports.vehicles', [
            'vehicles' => $this->vehicles,
        ]);
    }
}
