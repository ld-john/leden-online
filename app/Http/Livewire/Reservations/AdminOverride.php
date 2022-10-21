<?php

namespace App\Http\Livewire\Reservations;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AdminOverride extends Component
{
    /**
     * @var \App\Models\Vehicle
     */
    public $vehicle;
    public $broker_id;
    public $searchName;
    public $searchCompany;
    public $reserved;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->reserved = Reservation::where(
            'vehicle_id',
            $vehicle->id,
        )->first();
    }

    public function reserveVehicle()
    {
        if ($this->reserved) {
            $this->reserved->update([
                'status' => 'deleted',
            ]);
            $this->reserved->delete();
        }

        $customer = User::where('id', $this->broker_id)->first();
        $expiry = Carbon::now()
            ->addWeekdays(2)
            ->format('Y-m-d');

        Reservation::create([
            'customer_id' => $customer->id,
            'broker_id' => $customer->company_id,
            'vehicle_id' => $this->vehicle->id,
            'leden_user_id' => Auth::user()->id,
            'status' => 'active',
            'expiry_date' => $expiry,
        ]);
        return $this->redirect(route('reservation.index'));
    }

    public function render()
    {
        $brokers = User::query()
            ->where('role', 'broker')
            ->when($this->searchName, function ($query) {
                $query
                    ->where('firstname', 'like', '%' . $this->searchName . '%')
                    ->orWhere(
                        'lastname',
                        'like',
                        '%' . $this->searchName . '%',
                    );
            })
            ->when($this->searchCompany, function ($query) {
                $query->whereHas('company', function ($query) {
                    $query->where(
                        'company_name',
                        'like',
                        '%' . $this->searchCompany . '%',
                    );
                });
            })
            ->paginate(10);
        return view('livewire.reservations.admin-override', [
            'brokers' => $brokers,
        ]);
    }
}
