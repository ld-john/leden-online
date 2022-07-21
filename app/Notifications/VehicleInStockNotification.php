<?php

namespace App\Notifications;

use App\Vehicle;
use Illuminate\Notifications\Notification;

class VehicleInStockNotification extends Notification
{
    public Vehicle $vehicle;

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
            'vehicle' => $this->vehicle->id,
            'type' => 'vehicle',
            'message' =>
                'A ' .
                $this->vehicle->manufacturer->name .
                ' ' .
                $this->vehicle->model .
                ' has been marked as In Stock. If this vehicle required Dealer Fit Options, please contact Leden offices now.',
        ];
    }
}
