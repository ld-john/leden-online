<?php

namespace App\Http\Livewire\Order;

use App\Invoice;
use App\Order;
use App\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DuplicateOrder extends Component
{
    public $order;
    public $modalShow = false;
    public $duplicateQty;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function toggleDuplicateModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.order.duplicate-order');
    }

    public function duplicateOrder()
    {
        $vehicle = Vehicle::where('id', $this->order->vehicle_id)->first();
        $invoice = Invoice::where('id', $this->order->invoice_id)->first();

        for ($i = 1; $i <= $this->duplicateQty; $i++) {
            $newCar = $vehicle->replicate();
            $newCar->chassis = null;
            $newCar->reg = null;
            $newCar->orbit_number = null;
            $newCar->ford_order_number =
                $vehicle->ford_order_number . '-copy-' . $i;
            $newCar->save();
            $newInvoice = $invoice->replicate();
            $newInvoice->save();
            $newOrder = $this->order->replicate();
            $newOrder->vehicle_id = $newCar->id;
            $newOrder->invoice_id = $newInvoice->id;
            $newOrder->broker_ref = null;
            $newOrder->save();
        }
        notify()->success('Order successfully duplicated', 'Order Duplicated');
        return redirect()->route('order_bank');
    }
}
