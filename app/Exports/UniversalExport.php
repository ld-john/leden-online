<?php

namespace App\Exports;

use App\Vehicle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UniversalExport implements FromView
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
        return view('exports.universal', [
            'vehicles' => $this->vehicles,
        ]);
    }
}
