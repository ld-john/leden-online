<?php

namespace App\Notifications;

use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;

class DeliveryDateSetNotification extends Notification
{
    private Vehicle $vehicle;

    public function __construct(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'vehicle' => $this->vehicle,
            'type' => 'vehicle',
            'message' =>
                'A delivery date of ' .
                Carbon::parse($this->vehicle->order->delivery_date)->format(
                    'd/m/Y',
                ) .
                ' has been set on your ' .
                $this->vehicle->manufacturer->name .
                ' ' .
                $this->vehicle->model .
                '. Please fill in the delivery request form to accept or amend this delivery date',
        ];
    }
}
