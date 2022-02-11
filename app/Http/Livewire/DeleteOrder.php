<?php

namespace App\Http\Livewire;

use App\Invoice;
use App\Order;
use App\Vehicle;
use Livewire\Component;

class DeleteOrder extends Component
{

    public $order;
    public $deleteVehicle = 'no';
    public $vehicleStatus;
    public $modalShow = false;
    public $vehicle;

    public function mount(Order $order, Vehicle $vehicle)
    {
        $this->order = $order;
        $this->vehicle = $vehicle;
    }

    public function toggleDeleteModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render()
    {
        return view('livewire.delete-order');
    }

    public function deleteOrder()
    {
        if($this->deleteVehicle === 'yes')
        {
            Vehicle::destroy($this->vehicle->id);
        } else {
            $this->vehicle->vehicle_status = $this->vehicleStatus;
            $this->vehicle->save();
        }

        $invoice = $this->order->invoice->id;

        Invoice::destroy($invoice);
        Order::destroy($this->order->id);
        return redirect()->route('order_bank');
    }
}
