<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
    public $searchCompound;
    public $searchOrderNumber;
    public $searchOrbitNumber;
    public $searchReg;
    public $searchBuildDate;
    public $searchDueDate;
    public $searchDeliveryDate;
    public $searchStatus;
    public $searchCustomer;
    public $searchRegistrationDate;
    public $searchBrokerRef;
    public $searchBroker;
    public $searchFinanceBroker;
    public $searchDealer;
    public $paginate = 10;
    public $brokerID;
    public $dealerID;
    public $now;
    public $filterMissingOrbitNumber;
    public $filterBuildDate;
    public $filterDueDate;
    public $filterDeliveryDate;

    public function mount($status, $view): void
    {
        $this->now = date('Y-m-d h:i:s');

        $this->status = $status;

        $this->view = $view;
        if (Auth::user()->role === 'broker') {
            $this->brokerID = Auth::user()->company_id;
        } elseif (Auth::user()->role === 'dealer') {
            $this->dealerID = Auth::user()->company_id;
        }
    }

    public function markCompleted(Order $order): void
    {
        $vehicle = $order->vehicle;

        $vehicle->update(['vehicle_status' => 7]);

        $order->update(['completed_date' => $this->now]);
    }

    public function render(): Factory|View|Application
    {
        $orders = Order::whereHas('vehicle', function ($q) {
            $q->whereIn('vehicle_status', $this->status);
        })
            ->with([
                'vehicle',
                'vehicle.manufacturer',
                'customer:id,customer_name',
                'broker:id,company_name',
                'finance_broker:id,company_name',
                'dealer:id,company_name',
                'delivery',
            ])
            ->when($this->brokerID, function ($query) {
                $query->where('broker_id', $this->brokerID);
            })
            ->when($this->dealerID, function ($query) {
                $query->where('dealer_id', $this->dealerID);
            })
            ->when($this->searchID, function ($query) {
                $query->where('id', 'like', '%' . $this->searchID . '%');
            })
            ->when($this->searchModel, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'model',
                        'like',
                        '%' . $this->searchModel . '%',
                    );
                });
            })
            ->when($this->searchDerivative, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'derivative',
                        'like',
                        '%' . $this->searchDerivative . '%',
                    );
                });
            })
            ->when($this->searchOrderNumber, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'ford_order_number',
                        'like',
                        '%' . $this->searchOrderNumber . '%',
                    );
                });
            })
            ->when($this->searchOrbitNumber, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'orbit_number',
                        'like',
                        '%' . $this->searchOrbitNumber . '%',
                    );
                });
            })
            ->when($this->searchReg, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('reg', 'like', '%' . $this->searchReg . '%');
                });
            })
            ->when($this->searchDueDate, function ($query) {
                $query->where(
                    'due_date',
                    'like',
                    '%' . $this->searchDueDate . '%',
                );
            })
            ->when($this->searchRegistrationDate, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'vehicle_registered_on',
                        'like',
                        '%' . $this->searchRegistrationDate . '%',
                    );
                });
            })
            ->when($this->searchDeliveryDate, function ($query) {
                $query->where(
                    'delivery_date',
                    'like',
                    '%' . $this->searchDeliveryDate . '%',
                );
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'build_date',
                        'like',
                        '%' . $this->searchBuildDate . '%',
                    );
                });
            })
            ->when($this->searchBuildDate, function ($query) {
                $query->where(
                    'due_date',
                    'like',
                    '%' . $this->searchDueDate . '%',
                );
            })
            ->when($this->searchStatus, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where('vehicle_status', $this->searchStatus);
                });
            })
            ->when($this->searchCustomer, function ($query) {
                $query->whereHas('customer', function ($query) {
                    $query->where(
                        'customer_name',
                        'like',
                        '%' . $this->searchCustomer . '%',
                    );
                });
            })
            ->when($this->searchBrokerRef, function ($query) {
                $query->where(
                    'broker_ref',
                    'like',
                    '%' . $this->searchBrokerRef . '%',
                );
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
            ->when($this->searchFinanceBroker, function ($query) {
                $query->WhereHas('finance_broker', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchFinanceBroker . '%',
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
            ->when($this->searchCompound, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->where(
                        'compound',
                        'like',
                        '%' . $this->searchCompound . '%',
                    );
                });
            })
            ->when($this->filterMissingOrbitNumber, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->whereNull('orbit_number');
                });
            })
            ->when($this->filterBuildDate, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->whereNotNull('build_date');
                });
            })
            ->when($this->filterDueDate, function ($query) {
                $query->whereHas('vehicle', function ($query) {
                    $query->whereNotNull('due_date');
                });
            })
            ->when($this->filterDeliveryDate, function ($query) {
                $query->whereNotNull('delivery_date');
            })
            ->orderBy('created_at')
            ->paginate($this->paginate);

        return view('livewire.order.order-table', ['orders' => $orders]);
    }
}
