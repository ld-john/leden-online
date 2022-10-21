<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FinanceExports implements FromView
{
    protected object $orders;

    /**
     * @return void
     */
    public function __construct(object $orders)
    {
        $this->orders = $orders;
    }

    public function view(): View
    {
        return view('exports.finance', [
            'orders' => $this->orders,
        ]);
    }
}
