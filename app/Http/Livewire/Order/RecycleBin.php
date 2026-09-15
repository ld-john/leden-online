<?php

namespace App\Http\Livewire\Order;

use App\Enums\VehicleStatus;
use App\Models\Order;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class RecycleBin extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
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
    public $status;

    public function mount(): void
    {
        $this->status = VehicleStatus::cases();
    }

    public function restore($orderId): void
    {
        $order = Order::onlyTrashed()->findOrFail($orderId);

        $order->restore();

        // Optional: Dispatch a browser event or flash message
        session()->flash('message', 'Order restored successfully.');
    }

    public function forceDelete($orderId): void
    {
        $order = Order::onlyTrashed()->findOrFail($orderId);

        $order->forceDelete();

        session()->flash('message', 'Order permanently deleted.');
    }

    public function render(): View|Factory|\Illuminate\View\View
    {

        $orders = Order::onlyTrashed()
            ->latest()
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
            ->when($this->searchDueDate, function ($query) {
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
            ->when($this->searchDealer, function ($query) {
                $query->whereHas('dealer', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchDealer . '%',
                    );
                });
            })
            ->with('vehicle')
            ->paginate(10);

        return view('livewire.order.recycle-bin', ['orders' => $orders]);
    }
}
