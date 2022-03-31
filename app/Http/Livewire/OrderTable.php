<?php

namespace App\Http\Livewire;

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
    public $searchID;
    public $searchModel;
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
    public $brokerID;
    public $dealerID;

    public function mount($status)
    {
        $this->status = $status;
        if( Auth::user()->role === 'broker' ) {
            $this->brokerID = Auth::user()->company->id;
        } elseif ( Auth::user()->role === 'dealer' ) {
            $this->dealerID = Auth::user()->company->id;
        }
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

        return view('livewire.order-table', ['orders' => $orders]);
    }
}
