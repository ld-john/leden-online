<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BrokersStockDownload implements FromView
{
    private $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function view(): View
    {
        return view('exports.brokers-stock-download', [
            'vehicles' => $this->vehicles,
        ]);
    }
}
