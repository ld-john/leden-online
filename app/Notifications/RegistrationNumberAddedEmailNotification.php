<?php

namespace App\Notifications;

use App\Models\Vehicle;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegistrationNumberAddedEmailNotification extends Notification
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
            )
            ->action(
                'View the Vehicle',
                url(route('vehicle.show', $this->vehicle->id)),
            );
    }
}
