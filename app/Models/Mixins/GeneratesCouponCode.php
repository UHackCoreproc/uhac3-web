<?php

namespace UHacWeb\Models\Mixins;

trait GeneratesCouponCode
{
    public static function bootGeneratesCouponCode()
    {
        static::creating(function ($model) {
            $model->code = $model->generateCouponCode();
        });
    }

    /**
     * A sure method to generate a unique coupon code
     *
     * @return string
     */
    public static function generateCouponCode()
    {
        do {
            $salt = sha1(time() . mt_rand());
            $couponCode = substr($salt, 0, 8);
        } // Already in the DB? Fail. Try again
        while (self::couponCodeExists($couponCode));

        return $couponCode;
    }

    /**
     * Checks whether a coupon code exists in the database or not
     *
     * @param $couponCode
     * @return bool
     */
    private static function couponCodeExists($couponCode)
    {
        $couponCodeCount = self::where('code', '=', $couponCode)->limit(1)->count();

        return ($couponCodeCount > 0);
    }
}
