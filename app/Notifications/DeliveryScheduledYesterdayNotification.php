<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Notifications\Notification;

class DeliveryScheduledYesterdayNotification extends Notification
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

    public function toDatabase($notifiable): array
    {
        return [];
    }

    public function toArray($notifiable): array
    {
        return [
            'delivery' => $this->delivery->id,
            'type' => 'vehicle',
            'message' =>
                'A delivery shipped yesterday for a ' .
                $this->delivery->order->vehicle->manufacturer->name .
                ' ' .
                $this->delivery->order->vehicle->model .
                ', associated with Leden Order #' .
                $this->delivery->order?->id .
                '. The vehicle was registration number: ' .
                $this->delivery->order->vehicle->reg .
                '. Please ensure that paperwork is sent to Leden\'s offices.',
        ];
    }
}
