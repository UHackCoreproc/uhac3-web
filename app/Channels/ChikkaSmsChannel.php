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

        $chikkaClient = new ChikkaClient(
            config('ggpay.apis.chikka.client_id'), config('ggpay.apis.chikka.secret_key'), config('ggpay.apis.chikka.short_code'));

        $sms = new Sms(Carbon::now()->timestamp, $notifiable->mobile_number, $message);

        $smsTransporter = new SmsTransporter($chikkaClient, $sms);

        $smsTransporter->send();
    }

}
