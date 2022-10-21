<?php

namespace App\Notifications;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleReserved extends Notification
{
    use Queueable;

    private string $message;
    /**
     * @var \App\Models\Reservation
     */
    private Reservation $reservation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->greeting('Hello!')
            ->line(
                'You have successfully placed a reservation on Vehicle #' .
                    $this->reservation->vehicle_id,
            )
            ->line(
                'Please place an order with Leden\'s offices before ' .
                    Carbon::parse($this->reservation->expiry_date)->format(
                        'd/m/Y',
                    ),
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'reservation' => $this->reservation->id,
            'type' => 'vehicle',
            'message' =>
                'Reservation placed on Vehicle #' .
                $this->reservation->vehicle_id .
                ', Please place an order with Leden\'s office by ' .
                Carbon::parse($this->reservation->expiry_date)->format('d/m/Y'),
        ];
    }
}
