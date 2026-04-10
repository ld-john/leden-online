<?php

namespace App\Notifications;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class DeliveryAwaitingConfirmationNotification extends Notification
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing(['vehicle.manufacturer', 'broker']);
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $vehicle = $this->order->vehicle;
        return [
            'order_id' => $this->order->id,
            'vehicle_id' => $vehicle->id,
            'type' => 'vehicle',
            'message' => sprintf( '%s has requested delivery of %s %s (%s) Orbit: %s on %s',
                $this->order->broker->company_name,
                $vehicle->manufacturer->name,
                $vehicle->model,
                $vehicle->reg,
                $vehicle->orbit_number,
                Carbon::parse($this->order->delivery_date)->format('d/m/Y'),
            ),
        ];
    }
}
