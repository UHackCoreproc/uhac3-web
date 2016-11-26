<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\MobileNumber;
use UHacWeb\Models\User;

class MobileNumberTransformer extends TransformerAbstract
{

    public function transform(MobileNumber $mobileNumber)
    {
        return [
            'mobile_number'  => $mobileNumber->mobile_number,
            'code'           => $mobileNumber->verification_code,
        ];
    }

}