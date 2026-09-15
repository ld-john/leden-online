<?php

namespace App\Http\Livewire\Vehicle;

use App\Enums\VehicleStatus;
use App\Models\Vehicle;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class RecycleBin extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $searchID;
    public $searchMake;
    public $searchModel;
    public $searchDerivative;
    public $searchEngine;
    public $searchColour;
    public $searchType;
    public $searchChassis;
    public $searchRegistration;
    public $searchStatus;
    public $status;

    public function mount(): void
    {
        $this->status = VehicleStatus::cases();
    }

    public function restore($vehicleId): void
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($vehicleId);

        $vehicle->restore();

        // Optional: Dispatch a browser event or flash message
        session()->flash('message', 'Vehicle restored successfully.');
    }

    public function forceDelete($vehicleId): void
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($vehicleId);

        $vehicle->forceDelete();

        session()->flash('message', 'Vehicle permanently deleted.');
    }

    public function render(): Factory|View|\Illuminate\View\View
    {
        $vehicles = Vehicle::onlyTrashed()
            ->latest()
            ->with('manufacturer')
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
            ->when($this->searchStatus, function ($query) {
                $query->where('vehicle_status', $this->searchStatus);
            })
            ->paginate(10);

        return view('livewire.vehicle.recycle-bin', ['vehicles' => $vehicles]);
    }
}
