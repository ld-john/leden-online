<?php

namespace App\Http\Livewire;

use App\Order;
use App\Vehicle;
use DateTime;
use Exception;
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
    protected $rules = [
        'due_date' => 'nullable|date',
        'build_date' => 'nullable|date'
    ];

    public function toggleEditModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    /**
     * @throws Exception
     */
    public function mount(Order $order, Vehicle $vehicle)
    {
        $this->order = $order;
        $this->vehicle = $vehicle;
        $this->order_number = $vehicle->ford_order_number;
        $this->registration = $vehicle->reg;
        $this->orbit_number = $vehicle->orbit_number;
        if ( $order->vehicle->build_date && $order->vehicle->build_date != '0000-00-00 00:00:00') {

            $del = new DateTime( $order->vehicle->build_date);
            $this->build_date = $del->format( 'd/m/Y');

        }
        if ( $order->due_date && $order->due_date != '0000-00-00 00:00:00') {

            $del = new DateTime( $order->due_date);
            $this->due_date = $del->format( 'd/m/Y');

        }
        $this->vehicleStatus = $vehicle->vehicle_status;
    }

    public function saveOrder()
    {

        if ($this->due_date) {
            $this->due_date = DateTime::createFromFormat('d/m/Y', $this->due_date );
        }

        if ($this->build_date) {
            $this->build_date = DateTime::createFromFormat('d/m/Y', $this->build_date);
        }

        $this->validate();

        $this->vehicle->reg = $this->registration;
        $this->vehicle->vehicle_status = $this->vehicleStatus;
        $this->vehicle->orbit_number = $this->orbit_number;
        $this->vehicle->ford_order_number = $this->order_number;
        $this->vehicle->build_date = $this->build_date;
        $this->vehicle->save();

        $this->order->due_date = $this->due_date;
        $this->order->save();

        $this->due_date = ( $this->due_date ? $this->due_date->format( 'd/m/Y') : null );
        $this->build_date = ( $this->build_date ? $this->build_date->format( 'd/m/Y') : null );
        return $this->redirect(route('order_bank'));
    }

    public function render()
    {
        return view('livewire.quick-edit-order');
    }
}
