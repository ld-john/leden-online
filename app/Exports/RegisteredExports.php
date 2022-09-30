<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RegisteredExports implements FromView
{
    protected object $vehicles;

    /**
     * @return void
     */
    public function __construct(object $vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function view(): View
    {
        return view('exports.reports', [
            'vehicles' => $this->vehicles,
        ]);
    }
}
