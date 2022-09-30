<?php

namespace App\Http\Livewire\Reporting;

use App\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ReportingTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $months = [
        '1' => 'Jan',
        '2' => 'Feb',
        '3' => 'Mar',
        '4' => 'Apr',
        '5' => 'May',
        '6' => 'Jun',
        '7' => 'Jul',
        '8' => 'Aug',
        '9' => 'Sep',
        '10' => 'Oct',
        '11' => 'Nov',
        '12' => 'Dec',
    ];
    public $quarters = ['1', '2', '3', '4'];
    public $years = ['2020', '2021', '2022'];
    public $monthFilter;

    public function clickMonth($month, $year)
    {
        $this->monthFilter = [$month, $year];
    }

    public function getQueryString(): array
    {
        return [];
    }

    public $paginate = 10;
    public function render(): Factory|View|Application
    {
        $data = Vehicle::where(function ($query) {
            $query
                ->where('vehicle_status', '7')
                ->orWhere('vehicle_status', '6')
                ->orWhere('vehicle_status', '15');
        })
            ->when($this->monthFilter, function ($query) {
                $query
                    ->whereMonth('vehicle_registered_on', $this->monthFilter[0])
                    ->whereYear('vehicle_registered_on', $this->monthFilter[1]);
            })
            ->paginate($this->paginate);

        return view('livewire.reporting.reporting-table', [
            'vehicles' => $data,
        ]);
    }
}
