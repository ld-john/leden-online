<?php

namespace App\Http\Livewire\Order;

use App\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }

    public $status;
    public $view;
    public $searchID;
    public $searchModel;
    public $searchDerivative;
    public $searchOrderNumber;
    public $searchOrbitNumber;
    public $searchReg;
    public $searchBuildDate;
    public $searchDueDate;
    public $searchDeliveryDate;
    public $searchStatus;
    public $searchCustomer;
    public $searchBrokerRef;
    public $searchBroker;
    public $searchDealer;
    public $paginate = 10;
    public $brokerID;
    public $dealerID;
    public $now;

    public function mount($status, $view)
    {
        $this->now = date('Y-m-d h:i:s');

        $this->status = $status;
        $this->view = $view;
        if( Auth::user()->role === 'broker' ) {
            $this->brokerID = Auth::user()->company->id;
        } elseif ( Auth::user()->role === 'dealer' ) {
            $this->dealerID = Auth::user()->company->id;
        }
    }

    public function markCompleted(Order $order)
    {
        $vehicle = $order->vehicle;

        $vehicle->update(['vehicle_status' => 7]);

        $order->update(['completed_date' => $this->now]);
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
                'delivery_date',
                'broker_ref',
                'updated_at'
            )
            ->with([
                'vehicle:id,model,ford_order_number,build_date,derivative,reg,vehicle_status,orbit_number,vehicle_registered_on',
                'customer:id,customer_name',
                'broker:id,company_name',
                'dealer:id,company_name'
            ])
            ->when($this->brokerID, function ($query) {
                $query->where('broker_id', $this->brokerID);
            })
            ->when($this->dealerID, function ($query) {
                $query->where('dealer_id', $this->dealerID);
            })
            ->when($this->searchID, function ($query) {
                $query->where('id', 'like', '%'.$this->searchID.'%');
            })
            ->when($this->searchModel, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('model', 'like', '%'.$this->searchModel.'%');
                });
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
            ->when($this->searchDueDate, function ($query) {
                $query->where('due_date', 'like', '%'.$this->searchDueDate.'%');
            })
            ->when($this->searchDeliveryDate, function ($query) {
                $query->where('delivery_date', 'like', '%'.$this->searchDeliveryDate.'%');
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
            ->orderBy('created_at', 'asc')
            ->paginate($this->paginate);

        return view('livewire.order.order-table', ['orders' => $orders]);
    }
}