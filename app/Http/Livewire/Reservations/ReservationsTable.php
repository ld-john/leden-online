<?php

namespace App\Http\Livewire\Reservations;

use App\Notifications\ReservationDeleted;
use App\Reservation;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReservationsTable extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function getQueryString(): array
    {
        return [];
    }
    public $paginate = 10;
    public $hideDeleted = false;

    public function updatingHideDeleted()
    {
        $this->resetPage();
    }

    public function placeOrder(Reservation $reservation)
    {
        $vehicle = $reservation->vehicle();
        $vehicle->update([
            'broker_id' => $reservation->broker_id,
        ]);
        return $this->redirect(
            route('order.reserve', $reservation->vehicle_id),
        );
    }

    public function deleteReservation(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'deleted',
        ]);
        $admin = User::where('company_id', '7')->get();
        $reservation->delete();
        $reservation->customer->notify(new ReservationDeleted($reservation));
        foreach ($admin as $user) {
            $user->notify(new ReservationDeleted($reservation));
        }
    }

    public function render(): Factory|View|Application
    {
        if (\Auth::user()->role === 'admin') {
            $reservations = Reservation::query()
                ->when($this->hideDeleted, function ($q) {
                    $q->withTrashed();
                })
                ->latest()
                ->paginate($this->paginate);
        } else {
            $reservations = Reservation::query()
                ->where('broker_id', Auth::user()->company_id)
                ->latest()
                ->paginate($this->paginate);
        }
        return view('livewire.reservations.reservations-table', [
            'reservations' => $reservations,
        ]);
    }
}
