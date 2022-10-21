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

    public function via(): array
    {
        return ['database'];
    }

    public function toArray(): array
    {
        return [
            'vehicle' => $this->vehicle->id,
            'type' => 'vehicle',
            'message' =>
                'A ' .
                $this->vehicle->manufacturer->name .
                ' ' .
                $this->vehicle->model .
                ' Orbit Number - ' .
                $this->vehicle->orbit_number .
                ' has been marked as In Stock for Order #' .
                $this->vehicle->order?->id .
                '. If this vehicle required Dealer Fit Options, please contact Leden offices now.',
        ];
    }
}
