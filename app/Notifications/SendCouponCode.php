<?php

namespace UHacWeb\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use UHacWeb\Channels\ChikkaSmsChannel;
use UHacWeb\Models\Coupon;

class SendCouponCode extends Notification
{

    use Queueable;

    private $coupon;

    /**
     * Create a new notification instance.
     * @param Coupon $serviceRequest
     */
    public function __construct(Coupon $coupon)
    {
        $this->coupon = $coupon;
    }

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
        return "Your coupon code is: \"{$this->coupon->code}\".\n\nPlease redeem to the nearest " . config('app.name') . " merchant.";
    }
}
