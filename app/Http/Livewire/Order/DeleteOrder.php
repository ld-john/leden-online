<?php

namespace App\Http\Livewire\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Livewire\Component;

class DeleteOrder extends Component
{
    public $order;
    public $deleteVehicle = 'no';
    public $vehicleStatus;
    public $modalShow = false;
    public $vehicle;

    /**
     * Prepare the component with the details of the order and the vehicle
     * @param Order $order
     * @return void
     */
    public function mount(Order $order): void
    {
        $this->order = $order;
        $this->vehicle = $order->vehicle;
    }

    /**
     * Show or hide the Delete Modal based on it's current state
     * @return void
     */
    public function toggleDeleteModal(): void
    {
        $this->modalShow = !$this->modalShow;
    }

    /**
     * Render the component
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.order.delete-order');
    }

    /**
     * When the delete order button is pressed, delete the order and delete or update the vehicle based on the choices selected
     * @return Application|RedirectResponse|Redirector
     */
    public function deleteOrder()
    {
        if ($this->deleteVehicle === 'yes') {
            Vehicle::destroy($this->vehicle->id);
        } else {
            if ($this->vehicleStatus) {
                $this->vehicle->vehicle_status = $this->vehicleStatus;
            }
            $this->vehicle->save();
        }

        $invoice = $this->order->invoice->id;

        Invoice::destroy($invoice);
        Order::destroy($this->order->id);
        return redirect(request()->header('Referer'));
    }
}
