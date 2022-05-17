<?php

namespace App\Http\Livewire\Reservations;

use App\Reservation;
use Livewire\Component;

class ReservationsTable extends Component
{
    public $paginate = 10;

    public function placeOrder(Reservation $reservation)
    {
        $vehicle = $reservation->vehicle();
        $vehicle->update([
            'broker_id' => $reservation->broker_id
        ]);
        return $this->redirect(route('order.reserve', $reservation->vehicle_id));
    }

    public function render()
    {
        if (\Auth::user()->role === 'admin')
            $reservations = Reservation::withTrashed()->paginate($this->paginate);
        else {
            $reservations = Reservation::paginate($this->paginate);
        }
        return view('livewire.reservations.reservations-table', ['reservations' => $reservations]);
    }
}
