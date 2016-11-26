<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\Coupon;

class CouponTransformer extends TransformerAbstract
{

    public function transform(Coupon $coupon)
    {
        $couponTransform = [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'amount' => $coupon->amount,
            'sender_contact_no' => $coupon->sender_contact_no,
            'recipient_contact_no' => $coupon->recipient_contact_no
        ];

        if ($coupon->claimed_at) {
            $couponTransform['claimed_at'] = $coupon->claimed_at;
        }

        return $couponTransform;
    }
}