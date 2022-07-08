<?php

namespace App\Http\Livewire\Order;

use App\Order;
use App\Vehicle;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Date;
use Livewire\Component;

class QuickEditOrder extends Component
{
    public $order;
    public $vehicle;
    public $modalShow = false;
    public $vehicleStatus;
    public $due_date;
    public $orbit_number;
    public $registration;
    public $order_number;
    public $build_date;
    public $order_date;
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

    public function toggleEditModal()
    {
        $this->modalShow = !$this->modalShow;
    }

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
        if ($order->due_date && $order->due_date != '0000-00-00 00:00:00') {
            $del = new DateTime($order->due_date);
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

        $this->vehicle->reg = $this->registration;
        $this->vehicle->vehicle_status = $this->vehicleStatus;
        $this->vehicle->orbit_number = $this->orbit_number;
        $this->vehicle->ford_order_number = $this->order_number;
        $this->vehicle->build_date = $this->build_date;
        $this->vehicle->vehicle_registered_on = $this->registered_date;
        $this->vehicle->save();

        $order = $this->order;

        $order->due_date = $this->due_date;
        $order->created_at = $this->order_date;
        $order->save();

        return $this->redirect(route($this->return));
    }

    public function render()
    {
        return view('livewire.order.quick-edit-order');
    }
}
