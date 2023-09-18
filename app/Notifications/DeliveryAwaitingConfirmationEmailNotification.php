<?php

namespace App\Notifications;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryAwaitingConfirmationEmailNotification extends Notification
{
    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via(): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(
                'Delivery booking request ' .
                    $this->order->id .
                    ' - ' .
                    $this->order->customer->name() .
                    ' -  ' .
                    $this->order->vehicle->reg,
            )
            ->line(
                $this->order->broker->company_name .
                    ' has requested the delivery of ' .
                    $this->order->vehicle->manufacturer->name .
                    ' ' .
                    $this->order->vehicle->model .
                    ' - ' .
                    $this->order->vehicle->reg .
                    ' ' .
                    ' Orbit Number: ' .
                    $this->order->vehicle->orbit_number .
                    ' with a delivery date of ' .
                    Carbon::parse($this->order->delivery_date)->format('d/m/Y'),
            )
            ->action(
                'Click for the Delivery Form',
                route('delivery.show', $this->order->delivery_id),
            )
            ->line('Thank you!');
    }
}
