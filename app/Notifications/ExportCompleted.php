<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExportCompleted extends Notification
{
    use Queueable;

    protected $filename;

    /**
     * Create a new notification instance.
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => 'Export Ready',
            'message' => 'Your vehicle export is ready for download.',
            'file_url' => asset('storage/' . $this->filename),
            'filename' => $this->filename,
        ];

    }



}
