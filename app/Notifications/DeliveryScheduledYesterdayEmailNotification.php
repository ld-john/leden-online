<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryScheduledYesterdayEmailNotification extends Notification
{
    private Delivery $delivery;

    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->line(
                'A delivery was made yesterday for a ' .
                    $this->delivery->order->vehicle->manufacturer->name .
                    ' ' .
                    $this->delivery->order->vehicle->model .
                    ', associated with Leden Order #' .
                    $this->delivery->order?->id .
                    '. This vehicle has the registration number: ' .
                    $this->delivery->order?->vehicle?->reg .
                    '. please ensure paperwork is sent to Leden at handovers@leden.co.uk.',
            )
            ->action(
                'View the Order Here',
                url(route('order.show', $this->delivery->order->id)),
            );
    }
}
