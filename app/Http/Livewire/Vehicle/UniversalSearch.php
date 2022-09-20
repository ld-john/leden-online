<?php

namespace App\Http\Livewire\Vehicle;

use App\Vehicle;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function render()
    {
        $data = Vehicle::when($this->searchID, function ($query) {
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
            ->when($this->searchStatus, function ($query) {
                $query->where('vehicle_status', $this->searchStatus);
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
            })
            ->paginate($this->paginate);
        $status = Vehicle::all();
        $status = $status->map
            ->only(['vehicle_status'])
            ->flatten()
            ->unique();
        return view('livewire.vehicle.universal-search', [
            'vehicles' => $data,
            'status' => $status,
        ]);
    }
}
