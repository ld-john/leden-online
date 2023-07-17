<?php

namespace App\Notifications;

use App\Models\Vehicle;
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
        $broker_ref = $this->vehicle->order?->broker_ref ?? 'TBC';
        return [
            'vehicle' => $this->vehicle->id,
            'type' => 'delivery',
            'message' =>
                'A ' .
                $this->vehicle->manufacturer->name .
                ' ' .
                $this->vehicle->model .
                ' Order Number - ' .
                $broker_ref .
                ' has been marked as In Stock, associated with Leden Order #' .
                $this->vehicle->order?->id .
                '. If this vehicle required Dealer Fit Options, please contact Leden offices now.',
        ];
    }
}
