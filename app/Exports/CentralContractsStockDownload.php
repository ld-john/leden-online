<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CentralContractsStockDownload implements FromView
{

    private $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function view(): View
    {
        return view('exports.central-contracts-stock-download', ['vehicles' => $this->vehicles]);
    }
}
