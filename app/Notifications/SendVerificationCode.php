<?php

namespace UHacWeb\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use UHacWeb\Channels\ChikkaSmsChannel;

class SendVerificationCode extends Notification
{

    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [
            ChikkaSmsChannel::class,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toChikkaSms($notifiable)
    {
        return "Your verification code is: \"{$notifiable->verification_code}\".\n\nggPay";
    }
}
