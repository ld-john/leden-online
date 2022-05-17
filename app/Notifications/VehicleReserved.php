<?php

namespace App\Notifications;

use App\Reservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VehicleReserved extends Notification
{
    use Queueable;

    private $message;
    /**
     * @var Reservation
     */
    private $reservation;

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
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello!')
                    ->line('You have successfully placed a reservation on Vehicle #' . $this->reservation->vehicle_id)
                    ->line('Please place an order with Leden\'s offices before ' . Carbon::parse($this->reservation->expiry_date)->format('d/m/Y'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'reservation' => $this->reservation->id,
            'type' => 'vehicle',
            'message' => 'Reservation placed on Vehicle #' . $this->reservation->vehicle_id . ', Please place an order with Leden\'s office by ' . Carbon::parse($this->reservation->expiry_date)->format('d/m/Y')
        ];
    }
}
