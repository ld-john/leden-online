<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\VehicleHasBeenReserved;
use App\Notifications\VehicleReserved;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('reservations.index');
    }

    public function admin_create(Vehicle $vehicle)
    {
        return view('reservations.create', ['vehicle' => $vehicle]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function create(Vehicle $vehicle)
    {
        $customer = Auth::user()->id;
        $broker = Auth::user()->company_id;
        $vehicle_id = $vehicle->id;
        $expiry = Carbon::now()
            ->addWeekdays(2)
            ->format('Y-m-d');

        $reservation = new Reservation();

        $reservation->customer_id = $customer;
        $reservation->broker_id = $broker;
        $reservation->vehicle_id = $vehicle_id;
        $reservation->expiry_date = $expiry;

        $reservation->save();

        Auth::user()->notify(new VehicleReserved($reservation));

        $admin = User::where('company_id', '7')->get();

        foreach ($admin as $user) {
            $user->notify(new VehicleHasBeenReserved($reservation));
        }

        return redirect(route('vehicle.show', $vehicle));
    }

    public function extend(Reservation $reservation)
    {
        $new_date = Carbon::parse($reservation->expiry_date)->addWeekday(1);
        if ($reservation->status === 'active') {
            $reservation->update([
                'expiry_date' => $new_date,
                'status' => 'extended',
                'leden_user_id' => Auth::user()->id,
            ]);
        } else {
            $reservation->update([
                'expiry_date' => $new_date,
                'status' => 'extended_deadline_approaching',
                'leden_user_id' => Auth::user()->id,
            ]);
        }

        return redirect(route('reservation.index'));
    }

    public function toggle(User $user)
    {
        $user->update([
            'reservation_allowed' => !$user->reservation_allowed,
        ]);
        return redirect(route('user_manager'));
    }
}
