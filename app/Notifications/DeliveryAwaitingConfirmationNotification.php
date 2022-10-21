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
        $this->order = $order;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'order' => $this->order,
            'vehicle' => $this->order->vehicle,
            'type' => 'vehicle',
            'message' =>
                $this->order->broker->company_name .
                ' has requested delivery of ' .
                $this->order->vehicle->manufacturer->name .
                ' ' .
                $this->order->vehicle->model .
                ' Orbit Number: ' .
                $this->order->vehicle->orbit_number .
                ' on ' .
                Carbon::parse($this->order->delivery_date)->format('d/m/Y'),
        ];
    }
}
