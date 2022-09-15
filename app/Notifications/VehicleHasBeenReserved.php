<?php

namespace App\Notifications;

use App\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleHasBeenReserved extends Notification
{
    use Queueable;

    public Reservation $reservation;

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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'reservation' => $this->reservation->id,
            'type' => 'vehicle',
            'message' =>
                $this->reservation->customer->firstname .
                ' ' .
                $this->reservation->customer->lastname .
                ' from ' .
                $this->reservation->company->company_name .
                '  has placed an order on Vehicle #' .
                $this->reservation->vehicle_id .
                '; Reservation Expires on ' .
                Carbon::parse($this->reservation->expiry_date)->format('d/m/Y'),
        ];
    }
}
