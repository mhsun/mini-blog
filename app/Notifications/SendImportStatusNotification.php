<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendImportStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $status;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(bool $status)
    {
        $this->status = $status;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return config('admin.notify') ? ['mail'] : ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $line = "Your data has been imported successfully. Try to refresh the page to see
        the reflection or simply click the button below";

        if (!$this->status) {
            $line = "Failed to import data. Please try again";
        }

        return (new MailMessage)
            ->subject("Data import update")
            ->line($line)
            ->action('See Now', url('/admin/posts'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'status' => $this->status
        ];
    }
}
