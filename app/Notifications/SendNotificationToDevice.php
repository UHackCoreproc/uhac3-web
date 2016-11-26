<?php

namespace UHacWeb\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use UHacWeb\Channels\FirebaseChannel;

class SendNotificationToDevice extends Notification {
    use Queueable;

    public $device_id;
    public $data;
    public $title;
    public $body;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($device_id, $data, $title, $body)
    {
        $this->device_id = $device_id;
        $this->data      = $data;
        $this->title     = $title;
        $this->body      = $body;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return [
            FirebaseChannel::class
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'device_id' => $this->device_id,
            'data'      => $this->data,
            'title'     => $this->title,
            'body'      => $this->body
        ];
    }

}
