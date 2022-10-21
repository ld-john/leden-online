<?php

namespace App\Http\Livewire\Delivery;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\Permission;
use App\Notifications\DeliveryAwaitingConfirmationEmailNotification;
use App\Notifications\DeliveryAwaitingConfirmationNotification;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Notification;

class DeliveryForm extends Component
{
    use WithFileUploads;

    public $order;
    public $delivery;
    public $order_ref;
    public $customer_name;
    public $reg;
    public $chassis;
    public $funder;
    public $partner;
    public string $delivery_date;
    public string $earliest_delivery_date;
    public $delivery_address1;
    public $delivery_address2;
    public $delivery_town;
    public $delivery_city;
    public $delivery_postcode;
    public $contact_name;
    public $contact_number;
    public $funder_confirmation;
    protected $rules = [
        'funder_confirmation' => 'nullable|mimes:jpeg,jpg,pdf,doc,docx',
    ];

    /**
     * @throws Exception
     */
    public function mount(Order $order)
    {
        if ($this->order->delivery_date) {
            $del = new DateTime($this->order->delivery_date);
            $this->delivery_date = $del->format('Y-m-d');
            $this->earliest_delivery_date = $del->format('Y-m-d');
        }
        $this->order = $order;

        $this->order_ref = $order->order_ref;
        $this->customer_name = $order->customer->name();
        $this->reg = $order->vehicle->reg;
        $this->chassis = $order->vehicle->chassis;
        $this->funder = $order->invoice_company->company_name;
        $this->partner = $order->broker->company_name;

        if ($order->delivery) {
            $this->delivery = $order->delivery;
            $this->delivery_address1 = $this->delivery->delivery_address1;
            $this->delivery_address2 = $this->delivery->delivery_address2;
            $this->delivery_town = $this->delivery->delivery_town;
            $this->delivery_city = $this->delivery->delivery_city;
            $this->delivery_postcode = $this->delivery->delivery_postcode;
            $this->contact_name = $this->delivery->contact_name;
            $this->contact_number = $this->delivery->contact_number;
            $this->funder_confirmation = $this->delivery->funder_confirmation;
        } else {
            $this->delivery_address1 = $order->customer->address_1;
            $this->delivery_address2 = $order->customer->address_2;
            $this->delivery_town = $order->customer->town;
            $this->delivery_city = $order->customer->city;
            $this->delivery_postcode = $order->customer->postcode;
            $this->contact_name = $order->customer->name();
            $this->contact_number = $order->customer->phone_number;
        }
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.delivery.delivery-form');
    }

    public function clearFunderConfirmation()
    {
        $this->funder_confirmation = null;
    }

    public function requestBooking()
    {
        $this->validate();
        $order = $this->order;
        if ($this->delivery) {
            $delivery = $this->delivery;
        } else {
            $delivery = new Delivery();
        }
        $delivery->delivery_date = Carbon::parse($this->delivery_date)
            ->startOfDay()
            ->format('Y-m-d h:i:s');
        $delivery->delivery_address1 = $this->delivery_address1;
        $delivery->delivery_address2 = $this->delivery_address2;
        $delivery->delivery_town = $this->delivery_town;
        $delivery->delivery_city = $this->delivery_city;
        $delivery->delivery_postcode = $this->delivery_postcode;
        $delivery->contact_name = $this->contact_name;
        $delivery->contact_number = $this->contact_number;
        if ($this->funder_confirmation) {
            $delivery->funder_confirmation = $this->funder_confirmation->store(
                '/public/files',
            );
        }
        $delivery->save();
        $order->update([
            'delivery_id' => $delivery->id,
            'delivery_date' => $this->delivery_date,
        ]);
        $vehicle = $order->vehicle;
        $vehicle->update([
            'vehicle_status' => 5,
        ]);
        $permission = Permission::where('name', 'manages-deliveries')->first();
        $notify_user = $permission->users;
        foreach ($notify_user as $user) {
            $user->notify(new DeliveryAwaitingConfirmationNotification($order));
        }
        Notification::route('mail', 'deliveries@leden.co.uk')->notify(
            new DeliveryAwaitingConfirmationEmailNotification($order),
        );
        notify()->success(
            'Delivery Booked - Awaiting Confirmation',
            'Delivery Booked',
        );
        return redirect(route('manage_deliveries'));
    }

    public function cancelBooking()
    {
        $order = $this->order;
        $delivery = $this->delivery;
        $vehicle = $order->vehicle;
        $order->update([
            'delivery_id' => null,
        ]);
        $vehicle->update([
            'vehicle_status' => 1,
        ]);
        $delivery->delete();
    }
}
