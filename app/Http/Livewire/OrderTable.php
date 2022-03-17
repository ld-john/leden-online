<?php

namespace App\Http\Livewire;

use App\Order;
use Livewire\Component;

class OrderTable extends Component
{
    public $status;
    public $searchID;
    public $searchDerivative;
    public $searchOrderNumber;
    public $searchOrbitNumber;
    public $searchReg;
    public $searchBuildDate;
    public $searchDueDate;
    public $searchStatus;
    public $searchCustomer;
    public $searchBrokerRef;
    public $searchBroker;
    public $searchDealer;
    public $paginate = 10;

    public function mount($status)
    {
        $this->status = $status;
    }

    public function render()
    {
        $orders = Order::whereHas('vehicle', function($q){
                $q->whereIn('vehicle_status', $this->status);
            })->select(
                'id',
                'vehicle_id',
                'broker_id',
                'dealer_id',
                'customer_id',
                'order_ref',
                'due_date',
                'broker_ref',
            )
            ->with([
                'vehicle:id,model,ford_order_number,build_date,derivative,reg,vehicle_status,orbit_number',
                'customer:id,customer_name,company_name,preferred_name',
                'broker:id,company_name',
                'dealer:id,company_name'
            ])
            ->when($this->searchID, function ($query) {
                $query->where('id', 'like', '%'.$this->searchID.'%');
            })
            ->when($this->searchDerivative, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('derivative', 'like', '%'.$this->searchDerivative.'%');
                });
            })
            ->when($this->searchOrderNumber, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('ford_order_number', 'like', '%'.$this->searchOrderNumber.'%');
                });
            })
            ->when($this->searchOrbitNumber, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('orbit_number', 'like', '%'.$this->searchOrbitNumber.'%');
                });
            })
            ->when($this->searchReg, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('reg', 'like', '%'.$this->searchReg.'%');
                });
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('build_date', 'like', '%'.$this->searchBuildDate.'%');
                });
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->where('due_date', 'like', '%'.$this->searchDueDate.'%');
            })
            ->when($this->searchStatus, function ($query) {
                $query->whereHas('vehicle',function ($query) {
                    $query->where('vehicle_status', $this->searchStatus);
                });
            })
            ->when($this->searchCustomer, function ($query) {
                $query->whereHas('customer', function ($query) {
                    $query->where('customer_name', 'like', '%'.$this->searchCustomer.'%');
                });
            })
            ->when($this->searchBrokerRef, function ($query) {
                $query->where('broker_ref', 'like', '%'.$this->searchBrokerRef.'%');
            })
            ->when($this->searchBroker, function ($query) {
                $query->whereHas('broker', function ($query) {
                    $query->where('company_name', 'like', '%'.$this->searchBroker.'%');
                });
            })
            ->when($this->searchDealer, function ($query) {
                $query->whereHas('dealer', function ($query) {
                    $query->where('company_name', 'like', '%'.$this->searchDealer.'%');
                });
            })
            ->orderBy('id', 'asc')
            ->paginate($this->paginate);

        return view('livewire.order-table', ['orders' => $orders]);
    }
}
