<?php

namespace UHacWeb\Transformers;

use League\Fractal\TransformerAbstract;
use UHacWeb\Models\Country;

class CountryTransformer extends TransformerAbstract
{

    public function transform(Country $country)
    {
        return [
            'id'        => $country->id,
            'name'      => $country->name,
            'code'      => $country->code,
            'longitude' => $country->longitude,
            'latitude'  => $country->latitude,
        ];
    }

}