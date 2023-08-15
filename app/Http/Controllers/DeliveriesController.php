<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Notifications\DeliveryBookedNotification;
use App\Notifications\DeliveryScheduledTomorrowEmailNotification;
use App\Notifications\DeliveryScheduledYesterdayEmailNotification;
use Illuminate\Database\Eloquent\Collection;
use LaravelIdea\Helper\App\Models\_IH_Delivery_C;

class DeliveriesController extends Controller
{
    public function create(Order $order)
    {
        return view('deliveries.create', ['order' => $order]);
    }
    public function show(Delivery $delivery)
    {
        return view('deliveries.show', ['delivery' => $delivery]);
    }
    public function accept(Delivery $delivery)
    {
        $vehicle = $delivery->order->vehicle;
        $vehicle->update([
            'vehicle_status' => 6,
        ]);
        $brokers = User::where('company_id', $delivery->order->broker)->get();
        foreach ($brokers as $broker) {
            $broker->notify(new DeliveryBookedNotification($delivery));
        }
        notify()->success(
            'Delivery was booked successfully',
            'Delivery Booked',
        );
        return redirect(route('manage_deliveries'));
    }
    public function cancel(Delivery $delivery)
    {
        $delivery->delete();
        $vehicle = $delivery->order->vehicle;
        $vehicle->update([
            'vehicle_status' => 1,
        ]);
        notify()->success(
            'Delivery was cancelled successfully',
            'Delivery Cancelled',
        );
        return redirect(route('pipeline'));
    }
    public function edit(Delivery $delivery)
    {
        return view('deliveries.edit', ['delivery' => $delivery]);
    }

    public function testDeliveryEmails()
    {
        $delivery = Delivery::latest()->first();

        (new User())
            ->forceFill([
                'name' => 'Lee',
                'email' => 'lee@leden.co.uk',
            ])
            ->notify(
                new DeliveryScheduledYesterdayEmailNotification($delivery),
            );

        (new User())
            ->forceFill([
                'name' => 'Lee',
                'email' => 'lee@leden.co.uk',
            ])
            ->notify(new DeliveryScheduledTomorrowEmailNotification($delivery));

        return 'Test emails sent';
    }
}
