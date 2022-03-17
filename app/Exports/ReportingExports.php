<?php

namespace App\Exports;

use App\Vehicle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportingExports implements FromView
{
    protected $data;
    protected $type;

    /**
     * @return mixed
     */
    public function __construct(object $data, string $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function view() : View
    {
        if ($this->type === 'placed') {
            return view('exports.orders', [
                'data' => $this->data,
            ]);
        } else {
            return view('exports.registrations', [
                'data' => $this->data,
            ]);
        }

    }
}
