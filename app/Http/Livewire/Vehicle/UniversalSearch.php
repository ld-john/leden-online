<?php

namespace App\Http\Livewire\Vehicle;

use App\Exports\UniversalExport;
use App\Vehicle;
use Excel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UniversalSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }

    public $paginate = 10;
    public $searchID;
    public $searchOrbitNumber;
    public $searchMake;
    public $searchModel;
    public $searchFordOrderNumber;
    public $searchStatus;
    public $searchOrder;
    public $searchBroker;
    public $searchDealer;
    public $searchCustomer;
    public $searchType;
    public $searchDetails;
    public $searchChassisPrefix;
    public $searchChassis;
    public $searchRegistration;
    public $searchBuildDate;
    public $searchDueDate;
    public $searchBrokerRef;
    protected $vehicles;
    public $status;
    public $type;
    public $completedOrdersFilter = true;
    public $deliveriesBookedFilter = true;

    public function mount()
    {
        $vehicles = Vehicle::all();
        $this->type = $vehicles->map
            ->only(['type'])
            ->sort()
            ->flatten()
            ->unique();
        $this->status = $vehicles->map
            ->only(['vehicle_status'])
            ->sort()
            ->flatten()
            ->unique();
        $this->vehicles = Vehicle::paginate(10);
    }

    public function downloadCurrentData(): BinaryFileResponse
    {
        $data = $this->filterVehiclesForDisplay()->get();
        return Excel::download(
            new UniversalExport($data),
            'leden-vehicle-download.xls',
        );
    }

    public function render(): Factory|View|Application
    {
        $data = $this->filterVehiclesForDisplay()->paginate($this->paginate);
        return view('livewire.vehicle.universal-search', [
            'vehicles' => $data,
        ]);
    }

    private function filterVehiclesForDisplay(): Builder|Vehicle
    {
        return Vehicle::whereIn('vehicle_status', [
            '1',
            '3',
            '4',
            '5',
            '10',
            '11',
            '12',
            '13',
            '14',
            '15',
            '16',
        ])
            ->when($this->deliveriesBookedFilter, function ($query) {
                $query->orWhere('vehicle_status', '6');
            })
            ->when($this->completedOrdersFilter, function ($query) {
                $query->orWhere('vehicle_status', '7');
            })
            ->when($this->searchID, function ($query) {
                $query->where('id', 'like', '%' . $this->searchID . '%');
            })
            ->when($this->searchOrbitNumber, function ($query) {
                $query->where(
                    'orbit_number',
                    'like',
                    '%' . $this->searchOrbitNumber . '%',
                );
            })
            ->when($this->searchMake, function ($query) {
                $query->whereHas('manufacturer', function ($query) {
                    $query->where(
                        'name',
                        'like',
                        '%' . $this->searchMake . '%',
                    );
                });
            })
            ->when($this->searchModel, function ($query) {
                $query->where('model', 'like', '%' . $this->searchModel . '%');
            })
            ->when($this->searchFordOrderNumber, function ($query) {
                $query->where(
                    'ford_order_number',
                    'like',
                    '%' . $this->searchFordOrderNumber . '%',
                );
            })
            ->when($this->searchType, function ($query) {
                $query->where('type', $this->searchType);
            })
            ->when($this->searchStatus, function ($query) {
                $query->where('vehicle_status', $this->searchStatus);
            })
            ->when($this->searchDetails, function ($query) {
                $query
                    ->where('colour', 'like', '%' . $this->searchDetails . '%')
                    ->orWhere(
                        'derivative',
                        'like',
                        '%' . $this->searchDetails . '%',
                    )
                    ->orWhere(
                        'engine',
                        'like',
                        '%' . $this->searchDetails . '%',
                    )
                    ->orWhere(
                        'transmission',
                        'like',
                        '%' . $this->searchDetails . '%',
                    );
            })
            ->when($this->searchChassisPrefix, function ($query) {
                $query->where(
                    'chassis_prefix',
                    'like',
                    '%' . $this->searchChassisPrefix . '%',
                );
            })
            ->when($this->searchChassis, function ($query) {
                $query->where(
                    'chassis',
                    'like',
                    '%' . $this->searchChassis . '%',
                );
            })
            ->when($this->searchRegistration, function ($query) {
                $query->where(
                    'reg',
                    'like',
                    '%' . $this->searchRegistration . '%',
                );
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->where('build_date', $this->searchBuildDate);
            })
            ->when($this->searchDueDate, function ($query) {
                $query->where('due_date', $this->searchDueDate);
            })
            ->when($this->searchBrokerRef, function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where(
                        'broker_ref',
                        'like',
                        '%' . $this->searchBrokerRef . '%',
                    );
                });
            })
            ->when($this->searchOrder, function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->where('id', 'like', '%' . $this->searchOrder . '%');
                });
            })
            ->when($this->searchBroker, function ($query) {
                $query->whereHas('broker', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchBroker . '%',
                    );
                });
            })
            ->when($this->searchDealer, function ($query) {
                $query->whereHas('dealer', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchDealer . '%',
                    );
                });
            })
            ->when($this->searchCustomer, function ($query) {
                $query->whereHas('order', function ($query) {
                    $query->whereHas('customer', function ($query) {
                        $query->where(
                            'customer_name',
                            'like',
                            '%' . $this->searchCustomer . '%',
                        );
                    });
                });
            });
    }
}
