<?php

namespace App\Notifications;

use App\Models\Delivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryEtaRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Delivery $delivery)
    {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $delivery->order?->vehicle?->reg ?? 'the scheduled vehicle';

        return (new MailMessage)
            ->cc('deliveries@leden.co.uk')
            ->subject("ETA Request: Delivery scheduled for today (#{$this->delivery->id})")
            ->greeting("Hello,")
            ->line("Thank you for taking the delivery job for this vehicle today.")
            ->line("In order for us to set our customer's expectation could you please provide an estimated delivery time for today please?")
            ->line('If you could confirm as soon as possible that would be appreciated.')
            ->line("**Vehicle:** {$vehicle}")
            ->line("**Delivery Ref:** #{$this->delivery->id}")
            ->action('View Delivery Details', url("/deliveries/{$this->delivery->id}"));
    }
}
