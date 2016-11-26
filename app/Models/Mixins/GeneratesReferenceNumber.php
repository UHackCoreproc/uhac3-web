<?php

namespace UHacWeb\Models\Mixins;

trait GenerateReferenceNumber
{
    public static function bootGenerateReferenceNumber()
    {
        static::creating(function ($model) {
            $model->reference_number = $model->generateReferenceNumber();
        });
    }

    /**
     * A sure method to generate a unique reference number
     *
     * @return string
     */
    public static function generateReferenceNumber()
    {
        do {
            $salt = sha1(time() . mt_rand());
            $refNumber = substr($salt, 0, 256);
        } // Already in the DB? Fail. Try again
        while (self::referenceNumberExists($refNumber));

        return $refNumber;
    }

    /**
     * Checks whether a reference number exists in the database or not
     *
     * @param $key
     * @return bool
     */
    private static function referenceNumberExists($refNo)
    {
        $refNoCount = self::where('reference_number', '=', $refNo)->limit(1)->count();

        return ($refNoCount > 0);
    }
}
