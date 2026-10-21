<?php

namespace App\Notifications;

use App\Models\Delivery;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryEtaRequestNotification extends Notification
{
    use Queueable;

    public Delivery $delivery;
    public Vehicle $vehicle;

    /**
     * Create a new notification instance.
     */
    public function __construct($delivery, $vehicle)
    {
        $this->delivery = $delivery;
        $this->vehicle = $vehicle;
    }

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
        $vehicleRef = $this->vehicle->reg ?? $this->vehicle->chassis ?? 'the scheduled vehicle';

        $mailMessage = (new MailMessage)
            ->cc(config('leden.email.delivery'))
            ->subject("Delivery of {$vehicleRef}")
            ->greeting("Hello,")
            ->line("Thank you for taking the delivery job for this vehicle today. In order to set our customer's expectations could you please give us an indication of the estimated delivery time today. If you could also ensure your driver calls the customer an hour in advance that would be appreciated")
            ->line("We look forward to hearing from you.");

        # CC dealer
        if ($this->delivery->order?->dealer?->delivery_email) {
            $mailMessage->cc($this->delivery->order?->dealer->delivery_email);
        }

        return $mailMessage;
    }
}
