<?php

namespace App\Http\Livewire\Vehicle;

use App\Models\Vehicle;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class VehicleTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }

    public $paginate = 10;

    public $ringfenced;
    public $fordpipeline;
    public $stock;
    public $searchStatus;
    public $searchMake;
    public $searchModel;
    public $searchOrderNumber;
    public $searchDerivative;
    public $searchEngine;
    public $searchColour;
    public $searchType;
    public $searchChassis;
    public $searchRegistration;
    public $searchDueDate;
    public $searchBuildDate;
    public $searchDealer;
    public $searchBroker;
    public $searchTransmission;
    public $role;
    public $filterMissingOrbitNumber;
    public $filterDueDate;
    public $filterBuildDate;
    public $searchID;

    public function mount($ringfenced, $fordpipeline): void
    {
        $this->ringfenced = $ringfenced;
        $this->fordpipeline = $fordpipeline;
        $this->role = Auth::user()->role;
    }

    public function unRingFenceVehicle(Vehicle $vehicle): void
    {
        $vehicle->update(['ring_fenced_stock' => 0, 'broker_id' => null]);
    }

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $data = Vehicle::with('manufacturer:id,name')
            ->with('order:id,vehicle_id')
            ->with('dealer')
            ->with('broker')
            ->when($this->role === 'broker', function ($query) {
                $query->where('hide_from_broker', false);
            })
            ->when($this->role === 'broker' && $this->ringfenced, function (
                $query,
            ) {
                $query->where('broker_id', Auth::user()->company->id);
            })
            ->when($this->role === 'dealer', function ($query) {
                $query->where('hide_from_dealer', false);
            })
            ->where('ring_fenced_stock', $this->ringfenced)
            ->where('show_in_ford_pipeline', $this->fordpipeline)
            ->doesntHave('reservation')
            ->doesntHave('order')
            ->when($this->searchStatus, function ($query) {
                $query->where('vehicle_status', $this->searchStatus);
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
            ->when($this->searchTransmission, function ($query) {
                $query->where(
                    'transmission',
                    'like',
                    '%' . $this->searchTransmission . '%',
                );
            })
            ->when($this->searchOrderNumber, function ($query) {
                $query->where(
                    'ford_order_number',
                    'like',
                    '%' . $this->searchOrderNumber . '%',
                );
            })
            ->when($this->searchDerivative, function ($query) {
                $query->where(
                    'derivative',
                    'like',
                    '%' . $this->searchDerivative . '%',
                );
            })
            ->when($this->searchEngine, function ($query) {
                $query->where(
                    'engine',
                    'like',
                    '%' . $this->searchEngine . '%',
                );
            })
            ->when($this->searchColour, function ($query) {
                $query->where(
                    'colour',
                    'like',
                    '%' . $this->searchColour . '%',
                );
            })
            ->when($this->searchType, function ($query) {
                $query->where('type', 'like', '%' . $this->searchType . '%');
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
            ->when($this->searchDueDate, function ($query) {
                $query->where(
                    'due_date',
                    'like',
                    '%' . $this->searchDueDate . '%',
                );
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->where(
                    'build_date',
                    'like',
                    '%' . $this->searchBuildDate . '%',
                );
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
            ->when($this->searchBroker, function ($query) {
                $query->whereHas('broker', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchBroker . '%',
                    );
                });
            })
            ->when($this->searchID, function ($query) {
                $query
                    ->where('id', 'like', '%' . $this->searchID . '%')
                    ->orWhere(
                        'orbit_number',
                        'like',
                        '%' . $this->searchID . '%',
                    );
            })
            ->when($this->filterMissingOrbitNumber, function ($query) {
                $query->whereNull('orbit_number');
            })
            ->when($this->filterBuildDate, function ($query) {
                $query->whereNotNull('build_date');
            })
            ->when($this->filterDueDate, function ($query) {
                $query->whereNotNull('due_date');
            })
            ->paginate($this->paginate);

        $status = Vehicle::where('ring_fenced_stock', $this->ringfenced)
            ->where('show_in_ford_pipeline', $this->fordpipeline)
            ->doesntHave('order')
            ->get();
        $status = $status->map
            ->only(['vehicle_status'])
            ->flatten()
            ->unique();

        return view('livewire.vehicle.vehicle-table', [
            'vehicles' => $data,
            'status' => $status,
        ]);
    }
}
