<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Notifications\Notification;

class DeliveryScheduledTomorrowNotification extends Notification
{
    private Delivery $delivery;

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
            'delivery' => $this->delivery->id,
            'type' => 'vehicle',
            'message' =>
                'A delivery is scheduled for tomorrow for a ' .
                $this->delivery->order->vehicle->manufacturer->name .
                ' ' .
                $this->delivery->order->vehicle->model .
                ', associated with Leden Order #' .
                $this->delivery->order?->id .
                '. Please ensure that arrangements are made and paperwork is sent to Leden\'s offices.',
        ];
    }
}
