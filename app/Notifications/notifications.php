<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class notifications extends Notification
{
    use Queueable;

    private string|null $message;
    private string|null $order_id;
    private string|null $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $order_id, $type)
    {
        $this->message = $message;
        $this->order_id = $order_id;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        //return ['mail'];
        return ['database'];
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
            'message' => $this->message,
            'order_id' => $this->order_id,
            'type' => $this->type,
        ];
    }
}
