<?php

namespace App\Http\Controllers;

use App\Delivery;
use App\Notifications\DeliveryBookedNotification;
use App\Order;
use App\User;

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
}
