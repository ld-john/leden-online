<?php

namespace App\Http\Livewire;

use App\Vehicle;
use Livewire\Component;

class VehicleTable extends Component
{
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
    public $searchBuildDate;
    public $searchDealer;

    public function mount($ringfenced, $fordpipeline)
    {
        $this->ringfenced = $ringfenced;
        $this->fordpipeline = $fordpipeline;
    }

    public function render()
    {
        $data = Vehicle::select('id', 'orbit_number', 'ford_order_number', 'make', 'model', 'derivative', 'reg', 'engine', 'vehicle_status', 'colour', 'type', 'dealer_fit_options', 'factory_fit_options')
            ->with('manufacturer:id,name')
            ->with('order:id,vehicle_id')
            ->where('ring_fenced_stock', $this->ringfenced)
            ->where('show_in_ford_pipeline', $this->fordpipeline)
            ->doesntHave('order')
            ->when($this->searchStatus, function ($query) {
                $query->where('vehicle_status', $this->searchStatus);
            })
            ->when($this->searchMake, function ($query) {
                $query->whereHas('manufacturer',function ($query) {
                    $query->where('name', 'like', '%'.$this->searchMake.'%');
                });
            })
            ->when($this->searchModel, function ($query) {
                $query->where('model', 'like', '%'.$this->searchModel.'%');
            })
            ->when($this->searchOrderNumber, function ($query) {
                $query->where('ford_order_number', 'like', '%'.$this->searchOrderNumber.'%');
            })
            ->when($this->searchDerivative, function ($query) {
                $query->where('derivative', 'like', '%'.$this->searchDerivative.'%');
            })
            ->when($this->searchEngine, function ($query) {
                $query->where('engine', 'like', '%'.$this->searchEngine.'%');
            })
            ->when($this->searchColour, function ($query) {
                $query->where('colour', 'like', '%'.$this->searchColour.'%');
            })
            ->when($this->searchType, function ($query) {
                $query->where('type', 'like', '%'.$this->searchType.'%');
            })
            ->when($this->searchChassis, function ($query) {
                $query->where('derivative', 'like', '%'.$this->searchChassis.'%');
            })
            ->when($this->searchRegistration, function ($query) {
                $query->where('reg', 'like', '%'.$this->searchRegistration.'%');
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->where('build_date', 'like', '%'.$this->searchBuildDate.'%');
            })
            ->when($this->searchDealer, function ($query) {
                $query->whereHas('dealer',function ($query) {
                    $query->where('company_name', 'like', '%'.$this->searchDealer.'%');
                });
            })
            ->paginate($this->paginate);

        $status = Vehicle::select('id', 'orbit_number', 'ford_order_number', 'make', 'model', 'derivative', 'reg', 'engine', 'vehicle_status', 'colour', 'type', 'dealer_fit_options', 'factory_fit_options')
            ->with('order:id,vehicle_id')
            ->where('ring_fenced_stock', $this->ringfenced)
            ->where('show_in_ford_pipeline', $this->fordpipeline)
            ->doesntHave('order')
            ->get();
        $status = $status->map->only(['vehicle_status'])->flatten()->unique();

        return view('livewire.vehicle-table', ['vehicles' => $data, 'status' => $status]);
    }
}
