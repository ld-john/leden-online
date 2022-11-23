<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Notifications\Notification;

class RegistrationNumberAddedNotification extends Notification
{
    public Vehicle $vehicle;

    public function __construct($vehicle)
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
            'vehicle' => $this->vehicle,
            'type' => 'vehicle',
            'message' =>
                'A registration of ' .
                $this->vehicle->reg .
                ' has been added to ' .
                $this->vehicle->manufacturer->name .
                ' ' .
                $this->vehicle->model .
                ' Order Number: ' .
                $broker_ref .
                ', associated with Leden Order #' .
                $this->vehicle->order?->id,
        ];
    }
}
