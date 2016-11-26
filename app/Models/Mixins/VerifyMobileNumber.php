<?php

namespace UHacWeb\Models\Mixins;

trait VerifyMobileNumber
{
    public static function bootVerifyMobileNumber()
    {
        static::creating(function ($model) {
            $model->verification_code = $model->generateCode();
        });

        static::updating(function ($model) {
            if ( ! $model->user_id) {
                $model->verification_code = $model->generateCode();
            }
        });
    }

    public static function generateCode()
    {
        return str_pad(rand(0, pow(10, 4)-1), 4, '0', STR_PAD_LEFT);
    }
}
