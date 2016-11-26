<?php


namespace UHacWeb\Channels;


use GuzzleHttp\Client;
use UHacWeb\Notifications\SendNotificationToDevice;
use Exception;

class FirebaseChannel {

    /**
     * Send the given notification.
     *
     * @param  mixed                    $notifiable
     * @param  SendNotificationToDevice $notification
     *
     * @return void
     */
    public function send($notifiable, SendNotificationToDevice $notification)
    {
        $client = new Client();

        try {
            $client->post(config('ggpay.apis.firebase.base_url'), [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'key=' . config('ggpay.apis.firebase.access_key')
                ],
                'json'    => [
                    'to'           => $notification->device_id,
                    'data'         => $notification->data,
                    'notification' => [
                        'title' => $notification->title,
                        'body'  => $notification->body
                    ]
                ]
            ]);
        } catch (Exception $e) {
            // fail silently
        }
    }
}
