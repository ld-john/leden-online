<?php

namespace App\Http\Livewire\Order;

use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\Permission;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\DeliveryDateSetNotification;
use App\Notifications\RegistrationNumberAddedEmailNotification;
use App\Notifications\RegistrationNumberAddedNotification;
use App\Notifications\VehicleInStockEmailNotification;
use App\Notifications\VehicleInStockNotification;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuickEditOrder extends Component
{
    public $order;
    public $vehicle;
    public $vehicleStatus;
    public $due_date;
    public $orbit_number;
    public $registration;
    public $order_number;
    public $build_date;
    public $order_date;
    public $delivery_date;
    public $registered_date;
    public $view;
    public $now;
    public $return;
    protected $rules = [
        'vehicleStatus' => 'required',
        'order_number' => 'required',
        'due_date' => 'nullable|date',
        'build_date' => 'nullable|date',
    ];

    /**
     * @throws Exception
     */
    public function mount(Order $order, Vehicle $vehicle, $view)
    {
        $this->order = $order;
        $this->vehicle = $vehicle;
        $this->view = $view;

        if ($this->view === 'order') {
            $this->return = 'order_bank';
        } else {
            $this->return = 'manage_deliveries';
        }

        $this->now = date('Y-m-d');
        if (
            $order->vehicle->vehicle_registered_on &&
            $order->vehicle->vehicle_registered_on != '0000-00-00 00:00:00'
        ) {
            $tempDate = new DateTime($order->vehicle->vehicle_registered_on);
            $this->registered_date = $tempDate->format('Y-m-d');
        } else {
            $this->registered_date = null;
        }

        $this->order_number = $vehicle->ford_order_number;
        $this->registration = $vehicle->reg;
        $this->orbit_number = $vehicle->orbit_number;
        $orderDate = new DateTime($order->created_at);
        $this->order_date = $orderDate->format('Y-m-d');
        if (
            $order->vehicle->build_date &&
            $order->vehicle->build_date != '0000-00-00 00:00:00'
        ) {
            $del = new DateTime($order->vehicle->build_date);
            $this->build_date = $del->format('Y-m-d');
        }
        if (
            $order->delivery_date &&
            $order->delivery_date != '0000-00-00 00:00:00'
        ) {
            $del = new DateTime($order->delivery_date);
            $this->delivery_date = $del->format('Y-m-d');
        }
        if (
            $order->vehicle->due_date &&
            $order->vehicle->due_date != '0000-00-00 00:00:00'
        ) {
            $del = new DateTime($order->vehicle->due_date);
            $this->due_date = $del->format('Y-m-d');
        }
        $this->vehicleStatus = $vehicle->vehicle_status;
    }

    public function saveOrder()
    {
        if ($this->orbit_number === '') {
            $this->orbit_number = null;
        }

        $this->validate();

        $this->vehicle->update([
            'reg' => $this->registration,
            'vehicle_status' => $this->vehicleStatus,
            'orbit_number' => $this->orbit_number,
            'ford_order_number' => $this->order_number,
            'build_date' => $this->build_date,
            'due_date' => $this->due_date,
            'vehicle_registered_on' => $this->registered_date,
        ]);
        $order = $this->order;

        $brokers = User::where('company_id', $order->broker->id)->get();
        $permission = Permission::where('name', 'receive-emails')->first();
        $mailBrokers = $permission->users
            ->where('company_id', $order->broker->id)
            ->all();

        if ($this->vehicle->wasChanged('vehicle_status')) {
            if ($this->vehicle->vehicle_status === '7') {
                $this->order->update(['completed_date' => now()]);
            } elseif ($this->vehicle->vehicle_status === '1') {
                foreach ($brokers as $broker) {
                    $broker->notify(
                        new VehicleInStockNotification($this->vehicle),
                    );
                }
                foreach ($mailBrokers as $broker) {
                    $broker->notify(
                        new VehicleInStockEmailNotification($this->vehicle),
                    );
                }
            }
        }
        if ($this->vehicle->wasChanged('reg')) {
            foreach ($brokers as $broker) {
                $broker->notify(
                    new RegistrationNumberAddedNotification($this->vehicle),
                );
            }
            foreach ($mailBrokers as $broker) {
                $broker->notify(
                    new RegistrationNumberAddedEmailNotification(
                        $this->vehicle,
                    ),
                );
            }
        }

        $order->update([
            'delivery_date' => $this->delivery_date,
            'created_at' => $this->order_date,
        ]);

        if ($order->wasChanged('delivery_date')) {
            if ($order->delivery_date) {
                $brokers = User::where('company_id', $order->broker)->get();
                foreach ($brokers as $broker) {
                    $broker->notify(
                        new DeliveryDateSetNotification($this->vehicle),
                    );
                }
            }
        }
        OrderController::setProvisionalRegDate($this->vehicle);
        notify()->success('Order was updated successfully', 'Order Updated');
        return $this->redirect(route($this->return));
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.order.quick-edit-order');
    }
}
