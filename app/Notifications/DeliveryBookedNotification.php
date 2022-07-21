<?php

namespace App\Notifications;

use App\Delivery;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class DeliveryBookedNotification extends Notification
{
    public Delivery $delivery;
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'delivery' => $this->delivery,
            'type' => 'vehicle',
            'message' =>
                'Delivery of ' .
                $this->delivery->order->vehicle->manufacturer->name .
                ' ' .
                $this->delivery->order->vehicle->model .
                ' Orbit Number: ' .
                $this->delivery->order->vehicle->orbit_number .
                ' has been booked for the following' .
                Carbon::parse($this->delivery->delivery_date)->format('d/m/Y'),
        ];
    }
}
