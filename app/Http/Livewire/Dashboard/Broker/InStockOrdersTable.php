<?php

namespace App\Http\Livewire\Dashboard\Broker;

use App\Order;
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
            $query->where('vehicle_status', '1');
        })
            ->where('broker_id', Auth::user()->company_id)
            ->orderBy('delivery_date', 'DESC')
            ->paginate($this->paginate);
        return view('livewire.dashboard.broker.in-stock-orders-table', [
            'orders' => $orders,
        ]);
    }
}
