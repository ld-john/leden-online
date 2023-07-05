<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CentralContractsOrderDownload implements FromView
{
    private $orders;

    /**
     * @param $orders
     */
    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('exports.central-contracts-orders-download', ['orders' => $this->orders]);
    }
}
