<?php

namespace App\Http\Livewire\Dashboard\Broker;

use App\Enums\VehicleStatus;
use App\Models\Order;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class InStockOrdersTable extends Component
{
    public $paginate = '10';

    public function render(): Factory|View|Application
    {
        $orders = Order::whereHas('vehicle', function ($query) {
            $query->whereIn('vehicle_status', [VehicleStatus::IN_STOCK, VehicleStatus::IN_STOCK_REGISTERED]);
        })
            ->where('broker_id', Auth::user()->company_id)
            ->with('vehicle')
            ->orderBy('delivery_date', 'DESC')
            ->paginate($this->paginate);
        return view('livewire.dashboard.broker.in-stock-orders-table', [
            'orders' => $orders,
        ]);
    }
}
