<?php

namespace UHacWeb\Channels;

use Carbon\Carbon;
use Coreproc\Chikka\ChikkaClient;
use Coreproc\Chikka\Models\Sms;
use Coreproc\Chikka\Transporters\SmsTransporter;
use Illuminate\Notifications\Notification;

class ChikkaSmsChannel
{

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toChikkaSms($notifiable);

        $chikkaClient = new ChikkaClient(env('CHIKKA_CLIENT_ID'), env('CHIKKA_SECRET_KEY'), env('CHIKKA_SHORT_CODE'));

        $sms = new Sms(Carbon::now()->timestamp, $notifiable->mobile_number, $message);

        $smsTransporter = new SmsTransporter($chikkaClient, $sms);

        $smsTransporter->send();
    }

}
