<?php

namespace App\Http\Livewire\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RestoreOrder extends Component
{
    public Order $order;
    public ?Vehicle $vehicle;

    public function mount(Order $order): void
    {
        $this->order = $order;
        $this->vehicle = $order?->vehicle;
    }
    public function render(): Factory|View|Application
    {
        return view('livewire.order.restore-order');
    }

    public function restoreOrderAndVehicle()
    {
        $this->order->update([
            'deleted_at' => null,
        ]);

        $this->restoreInvoice();

        notify()->success(
            'Order #' .
                $this->order->id .
                ' restored successfully. Vehicle was still available.',
            'Order Restored',
        );

        return redirect()->route('order.show', $this->order->id);
    }

    public function restoreOrderNewVehicle()
    {
        $vehicle = $this->order->vehicle;
        $newVehicle = $vehicle->replicate();
        $newVehicle->created_at = Carbon::now();
        $newVehicle->orbit_number = null;
        $newVehicle->reg = null;
        $newVehicle->vehicle_registered_on = null;
        $newVehicle->vehicle_status = 4;
        $newVehicle->ford_order_number = null;
        $newVehicle->save();

        $this->order->update([
            'deleted_at' => null,
            'vehicle_id' => $newVehicle->id,
        ]);

        $this->restoreInvoice();
        notify()->success(
            'Order #' .
                $this->order->id .
                ' restored successfully. New Vehicle: ' .
                $newVehicle->id .
                ' ' .
                $newVehicle->niceName() .
                ' created and associated with order #' .
                $this->order->id,
            'Order Restored and Vehicle Duplicated',
        );

        return redirect()->route('order.show', $this->order->id);
    }

    /**
     * @return void
     */
    public function restoreInvoice(): void
    {
        $invoice = Invoice::where(
            'id',
            $this->order->invoice_id,
        )->withTrashed();

        $invoice->update([
            'deleted_at' => null,
        ]);
    }
}
