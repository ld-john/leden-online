<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleInStockEmailNotification extends Notification
{
    public Vehicle $vehicle;

    public function __construct($vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        $broker_ref = $this->vehicle->order?->broker_ref ?? 'TBC';
        return (new MailMessage())
            ->line(
                'A ' .
                    $this->vehicle->manufacturer->name .
                    ' ' .
                    $this->vehicle->model .
                    ' Order Number - ' .
                    $broker_ref .
                    ' has been marked as In Stock, associated with Leden Order #' .
                    $this->vehicle->order?->id .
                    '. If this vehicle required Dealer Fit Options, please contact Leden offices now.',
            )
            ->action(
                'View the Vehicle',
                url(route('vehicle.show', $this->vehicle->id)),
            );
    }
}
